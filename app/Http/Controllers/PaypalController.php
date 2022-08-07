<?php

namespace App\Http\Controllers;

use App\Config;
use App\Menu;
use App\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use PayPal\Api\Amount;
use PayPal\Api\Details;
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


class PaypalController extends Controller
{
    
    private $_api_context;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        /** setup PayPal api context **/
        $paypal_conf = \Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_conf['client_id'], $paypal_conf['secret']));
        $this->_api_context->setConfig($paypal_conf['settings']);
    }
    /**
     * Store a details of payment with paypal.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postPaymentWithpaypal(Request $request)
    {
        $plan = Package::findOrFail($request->plan_id);
        if (Session::has('coupon_applied')) {
            $amount_coupon = $plan->amount - Session::get('coupon_applied')['amount'];
        } else {
            $amount_coupon = $plan->amount;
        }

        $currency_code = Config::first()->currency_code;
        $currency_code = strtoupper($currency_code);

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $item_1 = new Item();

        $item_1->setName($plan->name) /** item name **/
            ->setCurrency($currency_code)
            ->setQuantity(1)
            ->setPrice($amount_coupon); /** unit price **/

        $item_list = new ItemList();
        $item_list->setItems(array($item_1));

        $amount = new Amount();

        $amount->setCurrency($currency_code)
            ->setTotal($amount_coupon);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription('Subscription');

        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(route('getPaymentStatus')) /** Specify return URL **/
            ->setCancelUrl(route('getPaymentFailed'));

        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));
        /** dd($payment->create($this->_api_context));exit; **/
        try {
            $payment->create($this->_api_context);
        } catch (\PayPal\Exception\PPConnectionException $ex) {
            if (\Config::get('app.debug')) {
                return back()->with('deleted', __('Connection timeout'));
                /** echo "Exception: " . $ex->getMessage() . PHP_EOL; **/
                /** $err_data = json_decode($ex->getData(), true); **/
                /** exit; **/
            } else {
                return back()->with('deleted', __('Some error occur, sorry for inconvenient'));
                /** die('Some error occur, sorry for inconvenient'); **/
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
        Session::put('plan', $plan);

        if (isset($redirect_url)) {
            /** redirect to paypal **/
            return redirect($redirect_url);
        }

        return back()->with('deleted', __('Unknown error occurred'));
    }

    public function getPaymentStatus(Request $request)
    {
        $menus = Menu::all();
        $user_email = Auth::user()->email;
        $com_email = Config::findOrFail(1)->w_email;

        Session::put('user_email', $user_email);
        Session::put('com_email', $com_email);

        /** Get the payment ID before session clear **/
        $payment_id = Session::get('paypal_payment_id');
        $plan = Session::get('plan');
        if (Session::has('coupon_applied')) {
            $amount_coupon = $plan->amount - Session::get('coupon_applied')['amount'];
        } else {
            $amount_coupon = $plan->amount;
        }
        /** clear the session payment ID **/
        Session::forget('paypal_payment_id');
        Session::forget('plan');
        if (empty($request->get('PayerID')) || empty($request->get('token'))) {
            return back()->with('deleted', __('Payment failed'));
        }

        $payment = Payment::get($payment_id, $this->_api_context);
        /** PaymentExecution object includes information necessary **/
        /** to execute a PayPal account payment. **/
        /** The payer_id is added to the request query parameters **/
        /** when the user is redirected from paypal back to your site **/
        $execution = new PaymentExecution();
        $execution->setPayerId($request->get('PayerID'));
        /**Execute the payment **/
        $result = $payment->execute($execution, $this->_api_context);
        /** dd($result);exit; /** DEBUG RESULT, remove it later **/
        if ($result->getState() == 'approved') {
            /** it's all right **/
            /** Here Write your database logic like that insert record or value in database if you want **/

            $payment_amount = $amount_coupon;
            $payment_method = 'paypal';
            $payment_status = 1;
            $plan_id = $plan->id;
            $checkout = new SubscriptionController;
            return $checkout->subscribe($payment_id, $payment_method, $plan_id, $payment_status, $payment_amount);

        } else {
            return redirect('/')->with('deleted', __('Payment failed'));
        }

    }

    public function getPaymentFailed()
    {
        return redirect('/')->with('deleted', __('Payment failed'));
    }

}
