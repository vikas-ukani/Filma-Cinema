<?php

namespace App\Http\Controllers;

use Anand\LaravelPaytmWallet\Facades\PaytmWallet;
use App\Config;
use App\Package;
use App\User;
use App\UserWallet;
use App\UserWalletHistory;
use App\WalletSettings;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use Redirect;
use Validator;


class WalletController extends Controller
{

    public function __construct()
    {
        /** PayPal api context **/
        $paypal_conf = \Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_conf['client_id'], $paypal_conf['secret']));
        $this->_api_context->setConfig($paypal_conf['settings']);

    }

    public function showWallet(Request $request)
    {
        $wallte_setting_enable = WalletSettings::where('enable_wallet',1)->first();

        if(isset($wallte_setting_enable) && $wallte_setting_enable != NULL){
            if (isset(Auth::user()->wallet)) {

                if (Auth::user()->wallet->status == 1) {
                    if (isset(Auth::user()->wallet->wallethistory)) {
                        $currentPage = LengthAwarePaginator::resolveCurrentPage();
    
                        $itemCollection = collect(Auth::user()->wallet->wallethistory);
    
                        $itemCollection = $itemCollection->sortByDesc('id');
    
                        // Define how many items we want to be visible in each page
                        $perPage = 7;
    
                        // Slice the collection to get the items to display in current page
                        $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
    
                        // Create our paginator and pass it to the view
                        $wallethistory = new LengthAwarePaginator($currentPageItems, count($itemCollection), $perPage);
    
                        // set url path for generted links
                        $wallethistory->setPath($request->url());
    
                        return view('user.wallet', compact('wallethistory'));
                    } else {
                        return view('user.wallet');
                    }
                } else {
    
                    return back()->with('deleted', __('Sorry your wallet is not active !'));
                }
    
            } else {
    
                return view('user.wallet');
            }
        }else{
            return back()->with('deleted',__('Wallet setting is disabled!'));
        }
       
    }

    public function choosepaymentmethod(Request $request)
    {

        $amount = $request->amount;
        $wallet_settings = WalletSettings::where('enable_wallet',1)->first();
        return view('user.walletpay', compact('amount', 'wallet_settings'));
    }

    public function addMoneyViaPayPal(Request $request)
    {

        $config = Config::first();
        $setcurrency = $config->currency_code; //USD
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
        $item_1 = new Item();
        $item_1->setName('Item 1')
        /** item name **/
            ->setCurrency($setcurrency)->setQuantity(1)
            ->setPrice($request->amount);
        /** unit price **/
        $item_list = new ItemList();
        $item_list->setItems(array(
            $item_1,
        ));
        $amount = new Amount();
        $amount->setCurrency($setcurrency)->setTotal($request->amount);
        $transaction = new Transaction();
        $transaction->setAmount($amount)->setItemList($item_list)->setDescription('Adding money in wallet');
        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(URL::to('wallet/success/using/paypal'))
            ->setCancelUrl(URL::to('/userwallet'));
        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)->setRedirectUrls($redirect_urls)->setTransactions(array(
            $transaction,
        ));

        try
        {
            $payment->create($this->_api_context);
        } catch (\PayPal\Exception\PPConnectionException $ex) {
            if (\Config::get('app.debug')) {

                return redirect()->route('user.wallet.show')->with('deleted', __('Connection timeout !'));
            } else {

                return redirect()->route('user.wallet.show')->with('deleted', __('Some error occur, Sorry for inconvenient'));
            }
        }
        foreach ($payment->getLinks() as $link) {
            if ($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }
        /** add payment ID to session **/
        Session::put('paypal_payment_id', $payment->getId());
        if (isset($redirect_url)) {
            /** redirect to paypal **/
            return Redirect::away($redirect_url);
        }

        return redirect()->route('user.wallet.show')->with('deleted', __('Unknown error occurred !'));

    }

    public function paypalSuccess(Request $request)
    {

        $wallet = UserWallet::where('user_id', Auth::user()->id)->first();
        $payment_id = Session::get('paypal_payment_id');
        Session::forget('paypal_payment_id');
        if (empty($request->get('PayerID')) || empty($request->get('token'))) {

            return redirect()->route('user.wallet.show')->with('deleted', __('Payment failed !'));
        }

        $payment = Payment::get($payment_id, $this->_api_context);
        $execution = new PaymentExecution();
        $execution->setPayerId($request->get('PayerID'));
        /**Execute the payment **/
        $response = $payment->execute($execution, $this->_api_context);

        if ($response->getState() == 'approved') {
            $transactions = $payment->getTransactions();
            $relatedResources = $transactions[0]->getRelatedResources();
            $sale = $relatedResources[0]->getSale();
            $saleId = $sale->getId();

            if (isset($wallet)) {

                // update money if already wallet exist
                if ($wallet->status == 1) {

                    $wallet->balance = $wallet->balance + $sale->amount->total;
                    $wallet->save();

                    //adding log in history

                    $walletlog = new UserWalletHistory;
                    $walletlog->wallet_id = $wallet->id;
                    $walletlog->type = 'Credit';
                    $walletlog->log = 'Added Amount via ' . ucfirst('paypal');
                    $walletlog->amount = $sale->amount->total;
                    $walletlog->txn_id = $payment_id;

                    //adding expire date
                    $days = 365;
                    $todayDate = date('Y-m-d');
                    $expireDate = date("Y-m-d", strtotime("$todayDate +$days days"));
                    $walletlog->expire_at = $expireDate;
                    $walletlog->save();

                    return redirect()->route('user.wallet.show')->with('added', __('Amount added successfully !'));

                } else {

                    return back()->with('deleted', __('Your wallet is not active yet ! contact support system !'));
                }

            } else {

                // add money
                $wallet = new UserWallet;
                $wallet->user_id = Auth::user()->id;
                $wallet->balance = $sale->amount->total;
                $wallet->status = 1;
                $wallet->save();

                //adding log in history

                $walletlog = new UserWalletHistory;
                $walletlog->wallet_id = $wallet->id;
                $walletlog->type = 'Credit';
                $walletlog->log = 'Added Amount via ' . ucfirst('paypal');
                $walletlog->amount = $sale->amount->total;
                $walletlog->txn_id = $payment_id;

                //adding expire date
                $days = 365;
                $todayDate = date('Y-m-d');
                $expireDate = date("Y-m-d", strtotime("$todayDate +$days days"));
                $walletlog->expire_at = $expireDate;
                $walletlog->save();

                $walletlog->save();

                return redirect()->route('user.wallet.show')->with('added', __('Amount added successfully !'));
            }

        }

    }

    public function addMoneyViaStripe(Request $request)
    {

        $expiry = explode('/', $request->expiry);
        $validator = Validator::make($request->all(), [
            'number' => 'required',
            'expiry' => 'required',
            'cvc' => 'required|max:3',
            'amount' => 'required',
        ]);

        $input = $request->all();

        if ($validator->passes()) {

            $input = array_except($input, array('_token'));

            $stripe = Stripe::make(env('STRIPE_SECRET'));

            if ($stripe == '' || $stripe == null) {

                return redirect()->route('user.wallet.show')->with('deleted', __('Stripe Key Not Found Please Contact your Site Admin !'));
            }

            try {

                $month = (int) $expiry[0];
                $year = (int) $expiry[1];

                $token = $stripe->tokens()->create([
                    'card' => [
                        'number' => $request->get('number'),
                        'exp_month' => $month,
                        'exp_year' => $year,
                        'cvc' => $request->get('cvc'),
                    ],
                ]);

                if (!isset($token['id'])) {
                    return redirect()->route('user.wallet.show')->with('deleted', __('The Stripe Token was not generated correctly !'));
                }

                $charge = $stripe->charges()->create([
                    'card' => $token['id'],
                    'currency' => 'USD',
                    'amount' => $request->amount,
                    'description' => "Add Money in wallet",
                ]);

                if ($charge['status'] == 'succeeded') {

                    $payment_id = $charge['id'];

                    $wallet = UserWallet::where('user_id', Auth::user()->id)->first();

                    if (isset($wallet)) {

                        // update money if already wallet exist
                        if ($wallet->status == 1) {

                            $wallet->balance = $wallet->balance + ($charge['amount'] / 100);
                            $wallet->save();

                            //adding log in history

                            $walletlog = new UserWalletHistory;
                            $walletlog->wallet_id = $wallet->id;
                            $walletlog->type = 'Credit';
                            $walletlog->log = 'Added Amount via ' . ucfirst('stripe');
                            $walletlog->amount = $charge['amount'] / 100;
                            $walletlog->txn_id = $payment_id;

                            //adding expire date
                            $days = 365;
                            $todayDate = date('Y-m-d');
                            $expireDate = date("Y-m-d", strtotime("$todayDate +$days days"));
                            $walletlog->expire_at = $expireDate;

                            $walletlog->save();

                            return redirect()->route('user.wallet.show')->with('added', 'Amount added successfully !');

                        } else {

                            return back()->with('deleted',__('Your wallet is not active yet ! contact support system !'));
                        }

                    } else {

                        // add money
                        $wallet = new UserWallet;
                        $wallet->user_id = Auth::user()->id;
                        $wallet->balance = $charge['amount'] / 100;
                        $wallet->status = 1;
                        $wallet->save();

                        //adding log in history

                        $walletlog = new UserWalletHistory;
                        $walletlog->wallet_id = $wallet->id;
                        $walletlog->type = 'Credit';
                        $walletlog->log = 'Added Amount via ' . ucfirst('stripe');
                        $walletlog->amount = $charge['amount'] / 100;
                        $walletlog->txn_id = $payment_id;

                        //adding expire date
                        $days = 365;
                        $todayDate = date('Y-m-d');
                        $expireDate = date("Y-m-d", strtotime("$todayDate +$days days"));
                        $walletlog->expire_at = $expireDate;

                        $walletlog->save();

                        return redirect()->route('user.wallet.show')->with('success', __('Amount added successfully !'));
                    }

                } else {

                    return redirect()->route('user.wallet.show')->with('deleted', __('Payment Failed!'));
                }

            } catch (Exception $e) {

                return redirect()->route('user.wallet.show')->with('deleted', $e->getMessage());
            } catch (\Cartalyst\Stripe\Exception\CardErrorException $e) {

                return redirect()->route('user.wallet.show')->with('deleted', $e->getMessage());
            } catch (\Cartalyst\Stripe\Exception\MissingParameterException $e) {

                return redirect()->route('user.wallet.show')->with('deleted', $e->getMessage());
            }

        } else {
            return redirect()->route('user.wallet.show')->with('deleted', __('All fields are required !'));
        }
    }

    public function addMoneyViaPaytm(Request $request)
    {

        $orderID = uniqid();
        $amount = round($request->amount, 2);
        $payment = PaytmWallet::with('receive');

        $payment->prepare([
            'order' => $orderID,
            'user' => Auth::user()->id,
            'mobile_number' => Auth::user()->mobile != null ? Auth::user()->mobile : '9999999999',
            'email' => Auth::user()->email,
            'amount' => $amount,
            'callback_url' => url('/wallet/success/using/paytm'),
        ]);

        return $payment->receive();
    }

    public function paytmsuccess()
    {

        $transaction = PaytmWallet::with('receive');

        $response = $transaction->response();

        if ($transaction->isSuccessful()) {

            $wallet = UserWallet::where('user_id', Auth::user()->id)->first();

            if (isset($wallet)) {

                // update money if already wallet exist
                if ($wallet->status == 1) {

                    $wallet->balance = $wallet->balance + $response['TXNAMOUNT'];
                    $wallet->save();

                    //adding log in history

                    $walletlog = new UserWalletHistory;
                    $walletlog->wallet_id = $wallet->id;
                    $walletlog->type = 'Credit';
                    $walletlog->log = 'Added Amount via ' . ucfirst('paytm');
                    $walletlog->amount = $response['TXNAMOUNT'];
                    $walletlog->txn_id = $transaction->getTransactionId();

                    //adding expire date
                    $days = 365;
                    $todayDate = date('Y-m-d');
                    $expireDate = date("Y-m-d", strtotime("$todayDate +$days days"));
                    $walletlog->expire_at = $expireDate;

                    $walletlog->save();

                    return redirect()->route('user.wallet.show')->with('added', __('Amount added successfully !'));

                } else {

                    return redirect('/')->with('deleted', __('Your wallet is not active yet ! contact support system !'));
                }

            } else {

                // add money
                $wallet = new UserWallet;
                $wallet->user_id = Auth::user()->id;
                $wallet->balance = $response['TXNAMOUNT'];
                $wallet->status = 1;
                $wallet->save();

                //adding log in history

                $walletlog = new UserWalletHistory;
                $walletlog->wallet_id = $wallet->id;
                $walletlog->type = 'Credit';
                $walletlog->log = 'Added Amount via ' . ucfirst('paytm');
                $walletlog->amount = $response['TXNAMOUNT'];
                $walletlog->txn_id = $transaction->getTransactionId();

                //adding expire date
                $days = 365;
                $todayDate = date('Y-m-d');
                $expireDate = date("Y-m-d", strtotime("$todayDate +$days days"));
                $walletlog->expire_at = $expireDate;

                $walletlog->save();

                return redirect()->route('user.wallet.show')->with('added', __('Amount added successfully !'));
            }

        } elseif ($transaction->isFailed()) {

            return redirect()->route('user.wallet.show')->with('deleted', $transaction->getResponseMessage());

        } elseif ($transaction->isOpen()) {
            //Transaction Open/Processing

        } else {

            return redirect()->route('user.wallet.show')->with('deleted', $transaction->getResponseMessage());
        }
    }

    /* Wallet checkout for order */
    public function checkout(Request $request)
    {
        $plan = Package::find($request->plan_id);
        $plan_id = $plan->id;
        $payment_amount = $request->amount;
        $payment_id = 'WALLET_PAYMENT_' . uniqid();
        $payment_method = 'wallet';
        $payment_status = 1;

        $auth = Auth::user();

        $checkout = new SubscriptionController;
        return $checkout->subscribe($payment_id, $payment_method, $plan_id, $payment_status, $payment_amount);

    }

}
