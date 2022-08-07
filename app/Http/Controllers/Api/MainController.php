<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\User;
use App\Actor;
use App\Ads;
use App\Director;
use App\Genre;
use App\HomeSlider;
use App\LandingPage;
use App\Menu;
use App\Movie;
use App\AppUiShorting;
use App\HideForMe;
use App\Package;
use App\Season;
use App\TvSeries;
use App\PricingText;
use App\Episode;
use App\HomeTranslation;
use App\Plan;
use App\Config;
use App\BannerAdd;
use Closure;
use App\PackageMenu;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Stripe\Customer;
use Stripe\Stripe;
use App\Faq;
use App\Audio;
use App\AudioLanguage;
use App\Subtitles;
use App\Wishlist;
use App\FooterTranslation;
use DB;
use App\Blog;
use App\Adsense;
use App\PaypalSubscription;
use App\AuthCustomize;
use Reminder;
use App\WatchHistory;
use App\Multiplescreen;
use App\HomeBlock;
use App\SplashScreen;
use App\AppSlider;
use App\AppConfig;
use App\CouponCode;
use App\PackageFeature;
use App\CouponApply;
use App\seo;
use App\Button;
use App\ManualPaymentMethod;
use App\LiveEvent;
use App\CustomPage;
use App\Language;
use App\ReminderMail;
use App\UserWalletHistory;
use App\AffilateHistory;
use App\Affilate;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Validator;

class MainController extends Controller
{ 
  use SendsPasswordResetEmails;

  public function __construct()
    {
        if (env('IS_INSTALLED') == 1) {
            $this->configs = Config::first();
            $this->menu_all = Menu::query();
            $this->g = Genre::query();
            $this->lang = AudioLanguage::query();
            $this->ad = Adsense::first();
            $this->homeslider = HomeSlider::query();
            $this->button = Button::first();
        }

    }

  public function home(Request $request){

    $secretData = $this->CheckSecretKey($request);
    
    if($secretData != ''){
      return $secretData;
    }

    $app_config = AppConfig::find(1);
    $plans = Package::with('pricing_texts')->get()->toArray();
    $plans_feature = PackageFeature::get()->toArray();
    $blog = Blog::with(['comments','comments.subcomments'])->get();
    $blogs = [];
  
    foreach($blog as $result){
      $blogs[] = array(
          'id'=>$result->id,
          'title' => $result->title,
          'detail' => strip_tags($result->detail),
          'slug' => $result->slug,
          'image' => $result->image,
          'user_id'=>$result->user_id,
          'is_active' => $result->is_active,
          'images' => $result->image,
          'created_at' => $result->created_at,
          'updated_at'=>$result->updated_at,
          'comments' => $result->comments
      );


    }
  

    $blocks = LandingPage::orderBy('position', 'asc')->get()->toArray();
    $config = Config::findOrFail(1);
    $auth_customize = AuthCustomize::first()->toArray();
    $adsense = Adsense::first()->toArray();
    $button = Button::first();
    $seo = seo::first();
    $audiolanguages = AudioLanguage::all()->toArray();
    
  
    return response()->json(array('login_img'=>$auth_customize, 'config'=>$config, 'plans' =>$plans,'plans_feature'=> $plans_feature,'blocks'=>$blocks, 'adsense' => $adsense,'blogs' => $blogs,'app_config'=>$app_config,'button' =>$button,'seo'=>$seo,'audiolanguages' => $audiolanguages), 200); 
   
  }
  public function faq(Request $request){

    $secretData = $this->CheckSecretKey($request);
    
    if($secretData != ''){
      return $secretData;
    }

    $faqs = Faq::all()->toArray();
    return response()->json(array('faqs' =>$faqs), 200);
  }

  public function slider(Request $request){

    $secretData = $this->CheckSecretKey($request);
    
    if($secretData != ''){
      return $secretData;
    }

    $slider = HomeSlider::all()->toArray();
    $app_slider = AppSlider::all()->toArray();

    return response()->json(array('slider'=>$slider,'app_slider'=>$app_slider), 200);
  }

  public function menu(Request $request){

    $secretData = $this->CheckSecretKey($request);
    
    if($secretData != ''){
      return $secretData;
    }

    $auth = Auth::user();
    $menu = Menu::all()->transform(function($item){
      $item['name'] = $item->getTranslations('name');
      return $item;
    })->toArray();
    $customPage = CustomPage::where('in_show_menu',1)->where('is_active',1)->get();
    //add conidtions
    return response()->json(array('menu'=>$menu, 'customPage'=>$customPage), 200);
  }
  public function movie(Request $request){

    $secretData = $this->CheckSecretKey($request);
    
    if($secretData != ''){
      return $secretData;
    }

    $movie = Movie::with('multilinks','subtitles','label')->get()->transform(function($ms){
      $ms['subtitle_path'] = url('/subtitles');
      $ms['keyword'] = $ms->getTranslations('keyword');
      $ms['description'] = $ms->getTranslations('description');
      $ms['detail'] = $ms->getTranslations('detail');
      return $ms;
    })->toArray();
    return response()->json(array('movie'=>$movie), 200);       
  }
  public function tvseries(Request $request){

    $secretData = $this->CheckSecretKey($request);
    
    if($secretData != ''){
      return $secretData;
    }

    $tvseries = TvSeries::with('seasons.episodes.multilinks','seasons.episodes.subtitles')->get()->transform(function($ts){
        
      $ts['subtitle_path'] = url('/subtitles');
      $ts['keyword'] = $ts->getTranslations('keyword');
      $ts['description'] = $ts->getTranslations('description');
      $ts['detail'] = $ts->getTranslations('detail');
      return $ts;
    })->toArray();
    return response()->json(array('tvseries'=>$tvseries), 200);    
  }
  public function movietv(Request $request){

    $secretData = $this->CheckSecretKey($request);
    
    if($secretData != ''){
      return $secretData;
    }

    $auth = Auth::user(); 
    $movieTvSeries = collect();
    $movie = Movie::with('movie_series','video_link','comments.subcomments','multilinks', 'subtitles')->get(); 
    $tvseries = TvSeries::with('seasons.episodes.video_link','comments.subcomments','seasons.episodes.multilinks', 'seasons.episodes.subtitles')->get(); 
    $movieTvSeries = $movieTvSeries->push($movie);
    $movieTvSeries = $movieTvSeries->push($tvseries)->flatten()->toArray(); 
    $top_movies_tv = array();
    $top_movies_tv = HomeBlock::orderBy('id','desc')->where('is_active','=','1')->get();
      return response()->json(array('data'=>$movieTvSeries,'top_movies_tv'=>$top_movies_tv), 200);
  }
  public function index(Request $request){

    $secretData = $this->CheckSecretKey($request);
    
    if($secretData != ''){
      return $secretData;
    }

    $auth = Auth::user();    
    //$home_translations = HomeTranslation::all()->toArray();
    $actor = Actor::all()->toArray();
    $audio =  AudioLanguage::all()->toArray();
    $subtitles = Subtitles::all()->toArray();
    $director = Director::all()->toArray();
    $genre = Genre::all()->toArray();
    
    return response()->json(array( 'auth' =>$auth,'actor'=>$actor,'director'=>$director,'audio'=>$audio,'subtitles '=>$subtitles ,'genre'=>$genre), 200);   
}

  public function userProfile(Request $request){

    $secretData = $this->CheckSecretKey($request);
    
    if($secretData != ''){
      return $secretData;
    }

    $user = Auth::user();
    $code = $user->token();
    $app_config = AppConfig::first();
    Stripe::setApiKey(env('STRIPE_SECRET'));
    if ($user->stripe_id != null) {
     $customer = Customer::retrieve($user->stripe_id);
    } 
    $current_subscription = null;
    $payment = null;
    $id = null;
    $start = null;
    $end = null;    
    $payid = null;
    $active = "0";
    $screen = null;
    $planid = null;
    $downloadlimit = null;
    $remove_ads = 0; 
    $paypal = PaypalSubscription::with('plan')->where('user_id', $user->id)->orderBy('created_at')->get();
    $affilate = Affilate::first();

        if (!$affilate || $affilate->enable_affilate != 1) {
            abort(404);
        }

        if (auth()->user()->refer_code == '') {

            auth()->user()->update([
                'refer_code' => User::createReferCode(),
            ]);

        }
        $wallet = collect(Auth::user()->wallethistory);

        $aff_history = auth()->user()->getReferals()->with(['user' => function ($q) {
            return $q->select('id', 'email');
        }])->wherehas('user')->paginate(10);

        $earning = auth()->user()->getReferals()->wherehas('user')->sum('amount');

    
    
    $current_date = Carbon::now()->toDateString();
    if (isset($customer)) {         
     $alldata = $user->subscriptions;
     $data = $alldata->last();      
    } 
    if (isset($paypal) && $paypal != null && count($paypal)>0) {
      $last = $paypal->last();
    } 
    $stripedate = isset($data) ? $data->created_at : null;
    $paydate = isset($last) ? $last->created_at : null;
    if($stripedate > $paydate){
      if($user->subscribed($data->name) && date($current_date) <= date($data->subscription_to)){
        $current_subscription = $data->name;
        $plan = Package::where('plan_id',$data->stripe_plan)->first();
        if($user->subscription($data->name)->cancelled()){ 
          $active = "0";
        }
        else{
          $active = "1";
        }
        $id = $data->id;
        $planid = $plan->id;
        $payment = 'stripe';
        $start = $data->subscription_from;
        $end = $data->subscription_to;
        $payid = $data->stripe_id;
        $screen = isset($plan) ? $plan->screens : null;
        $downloadlimit = isset($plan) ? $plan->downloadlimit : null; 
        if(isset($app_config) && $app_config->remove_ads == 1){
          $remove_ads = $plan->ads_in_app;
        }else{
          $remove_ads = 0;
        }
        

      }
    }
    elseif($stripedate < $paydate){
      if (date($current_date) <= date($last->subscription_to)) {
        if($last->package_id == 0 || $last->package_id == 100 || $last->method == 'free'){
           $current_subscription = null;
           $payment = 'Free';
        }
        else{
            $current_subscription = $last->plan->name;
            $payment = $last->method;
        }
        $id = $last->id;
        $planid = $last->package_id;
        $start = $last->subscription_from;
        $end = $last->subscription_to;
        $active = "$last->status";
        $payid = $last->payment_id;
        $screen = isset($last->plan) ? $last->plan->screens : null;
        $downloadlimit = isset($last->plan) ? $last->plan->downloadlimit :  null;
        if(isset($app_config) && $app_config->remove_ads == 1){
          $remove_ads = $last->plan->ads_in_app;
        }else{
          $remove_ads = 0;
        } 
        
      }
    }
    if($active == 1 && $screen > 0) {
      $multiplescreen = Multiplescreen::where('user_id',$user->id)->first();
      if(!isset($multiplescreen)){
        $multiplescreen = Multiplescreen::create([
          'pkg_id' => $planid,
          'user_id' => $user->id,
          'screen1' => $screen >= 1 ? $user->name :  null,
          'screen2' => $screen >= 2 ? 'screen2' :  null,
          'screen3' => $screen >= 3 ? 'screen3' :  null,
          'screen4' => $screen >= 4 ? 'screen4' :  null
               
        ]);
      }
    }    
      
    return response()->json(array('code'=>$code->id,'user'=>$user,'paypal' => $paypal,'aff_history'=>$aff_history,'earning'=>$earning ,'affilate' =>$affilate, 'current_date'=> $current_date,'payment'=>$payment, 'id'=>$id,'current_subscription'=>$current_subscription, 'wallet' => $wallet, 'payid' => $payid, 'start' => $start, 'end' => $end,'active'=>$active,'screen' => $screen, 'limit' => $downloadlimit,'remove_ads' => $remove_ads), 200);
  }

  public function package(Request $request){

    $secretData = $this->CheckSecretKey($request);
    
    if($secretData != ''){
      return $secretData;
    }

    $package = Package::all()->toArray();
    $pricingtexts = PricingText::all()->toArray();
    $package_feature = PackageFeature::get()->toArray();
    return response()->json(array('package'=>$package,'package_feature'=>$package_feature,'pricingtexts' => $pricingtexts), 200);
  }
  public function RecentMovies(Request $request){

    $secretData = $this->CheckSecretKey($request);
    
    if($secretData != ''){
      return $secretData;
    }

    $recent = Movie::orderBy('id', 'DESC')->take(30)->get()->toArray();
    return response()->json(array('recent'=>$recent), 200);
  }

  public function Recenttvseries(Request $request){

    $secretData = $this->CheckSecretKey($request);
    
    if($secretData != ''){
      return $secretData;
    }

    $tvseries = TvSeries::orderBy('id', 'DESC')->take(30)->get()->toArray();
    return response()->json(array('tvseries'=>$tvseries), 200);            
     
  }
  
 

public function MovieByCategory(Request $request,$id){

  $secretData = $this->CheckSecretKey($request);
    
  if($secretData != ''){
    return $secretData;
  }

    $auth = Auth::user();
    $movie = Movie::with('movie_series','video_link','comments.subcomments','multilinks','subtitles')
             ->whereHas('menus',function($query) use ($id){
                $query->where('menu_id', $id);
            })->get(); 

    $tvseries = TvSeries::with('seasons.episodes.video_link','comments.subcomments','seasons.episodes.multilinks', 'seasons.episodes.subtitles')
                  ->whereHas('menus',function($query) use ($id){
                      $query->where('menu_id', $id);
                  })->get();

    $movieCount = count($movie);
    $tvCount = count($tvseries);

    if($tvCount == 0 && $movieCount == 0){

      $movieTvSeries = null; 
      return response()->json(array('auth' =>$auth,'data'=>$movieTvSeries), 200);  
    }
    else{
      if($movieCount == 0){

         $movieTvSeries = array($tvseries); 
         return response()->json(array('auth' =>$auth,'data'=>$movieTvSeries), 200);

      }
      else{
        if($tvCount == 0){
         $movieTvSeries = array($movie); 
         return response()->json(array('auth' =>$auth,'data'=>$movieTvSeries), 200);
        }
        else{
        
         $movieTvSeries = array_merge(array($tvseries,$movie));  
         return response()->json(array('auth' =>$auth,'data'=>$movieTvSeries), 200);
        }
      }
    }   

    //return response()->json(array('auth' =>$auth,'movie'=>$movie,'tvseries'=>$tvseries), 200);          
  }

  public function episodes(Request $request,$id){
    
    $secretData = $this->CheckSecretKey($request);
    
    if($secretData != ''){
      return $secretData;
    }

      $season = Season::find($id); 
      if(isset($season)){
       $episodes = Episode::with('video_link','multilinks','subtitles')->where('seasons_id',$id)->get();
        return response()->json(array('episodes' =>$episodes), 200);  
      }
      else{
           return response()->json('error', 400);
        }    
  }
  
  public function updateprofile(Request $request)
  {
    $secretData = $this->CheckSecretKey($request);
    
    if($secretData != ''){
      return $secretData;
    }

    $auth = Auth::user();

   
    $input = $request->all();

   
      if ($file = $request->file('image')) {
        if ($auth->image != null) {      
          $image_file = @file_get_contents(public_path().'/images/user/'.$auth->image);
          if($image_file){            
            unlink(public_path().'/images/user/'.$auth->image);
          }
        }
        $name = time().$file->getClientOriginalName();
        $file->move('images/user/', $name);
        $input['image'] = $name;
      }
      if($request->new_password != NULL){
          $request->validate([
            'current_password' => 'required',
          ]);
         if (Hash::check($request->current_password, $auth->password)){
            $input['new_password'] = bcrypt($input['new_password']); 
         }
        else{
          return response()->json('error: password doesnt match', 400);
        }
      }

      if (isset($request->dob)) {
        $dateOfBirth = $request->dob;
        // $user->dob = $request->dob;
        $today = date("Y-m-d");
        $diff = date_diff(date_create($dateOfBirth), date_create($today));
        $age = $diff->format('%y');
       $input['age'] = $age;

    }

      $auth->update([        
        'name' => isset($input['name']) ? $input['name'] : $auth->name,
        'email' =>  isset($input['email']) ? $input['email'] : $auth->email ,
        'password' => isset($input['new_password']) ? $input['new_password'] : $auth->password,
        'mobile' => isset($input['mobile']) ? $input['mobile'] : $auth->mobile,
        'dob' => isset($input['dob']) ? $input['dob'] : $auth->dob,
        'age'=> isset($input['age']) ? $input['age'] : $auth->age,
        'image' =>  isset($input['image']) ? $input['image'] : $auth->image,
      ]);
      $auth->save();
      return response()->json(array('auth' =>$auth), 200);
    
  }
  public function add_wishlist(Request $request)
  {
    $secretData = $this->CheckSecretKey($request);
    
    if($secretData != ''){
      return $secretData;
    }

    $auth = Auth::user();
    $wishlist = null;
    if($request->type == 'M'){
      $wishlist = Wishlist::where('movie_id', $request->id)
                        ->where('user_id', $auth->id)->first();
      if (isset($wishlist)){
        $wishlist->update(['added' => $request->value]);
      } 
      else {
        $wishlist = Wishlist::create([
          'user_id' => $auth->id,
          'movie_id' => $request->id,
          'added' => $request->value,
        ]);
      }
    } 
    elseif ($request->type === 'S') {
      $wishlist = Wishlist::where('season_id', $request->id)
                        ->where('user_id', $auth->id)->first();
      if (isset($wishlist)){
        $wishlist->update(['added' => $request->value]);
      } 
      else {
        $wishlist = Wishlist::create([
          'user_id' => $auth->id,
          'season_id' => $request->id,
          'added' => $request->value,
        ]);
      }
    } 
    else{
      return response()->json('error', 400);
    }   
   // if($wishlist != null){$wishlist = $wishlist->added;}
    return response()->json($wishlist, 200);
  }

  public function removeseason(Request $request, $id)
  {
    $secretData = $this->CheckSecretKey($request);
    
    if($secretData != ''){
      return $secretData;
    }

    $auth = Auth::user();
    $show = Wishlist::where('season_id', $id)->where('user_id', $auth->id)->first();
    if(isset($show)){
      $show->update(['added' => '0']);
      return response()->json($show, 200);
    }else{
      return response()->json('error', 400);
    }
  }

  public function removemovie(Request $request,$id)
  {
    $secretData = $this->CheckSecretKey($request);
    
    if($secretData != ''){
      return $secretData;
    }

    $auth = Auth::user();
    $movie = Wishlist::where('movie_id', $id)->where('user_id', $auth->id)->first();
    if(isset($movie)){
      $movie->update(['added' => '0']);
      return response()->json($movie, 200);
    }
    else{
      return response()->json('error', 400);
    }
  }
   
  public function show_wishlist(Request $request){

    $secretData = $this->CheckSecretKey($request);
    
    if($secretData != ''){
      return $secretData;
    }

    $user = Auth::user();
    $wishlist = null;
    $wishlist = Wishlist::where('user_id',$user->id)->where('added','1')->get();
    return response()->json(array('wishlist' =>$wishlist), 200);
  }

  public function check_wishlist(Request $request,$type,$id){

    $secretData = $this->CheckSecretKey($request);
    
    if($secretData != ''){
      return $secretData;
    }

    $user = Auth::user();
    $wishlist = null;
    if($type == 'M'){
      $wishlist = $user->wishlist->where('movie_id',$id)->first();
    }
    elseif($type == 'S'){
      $wishlist = $user->wishlist->where('season_id',$id)->first();
    }
    else{
      return response()->json('error', 400);
    }   
    if($wishlist != null){$wishlist = $wishlist->added;}
    else{$wishlist = 0;}
    return response()->json(array('wishlist' =>$wishlist), 200);
  }

  public function watch_history(Request $request){

    $secretData = $this->CheckSecretKey($request);
    
    if($secretData != ''){
      return $secretData;
    }

    $watch_history = WatchHistory::with('movies','tvseries.seasons')
                      ->where('user_id', Auth::user()->id)->get();
    return response()->json(array('watch_history' =>$watch_history), 200); 
  }
  public function watchistorydelete(Request $request){

    $secretData = $this->CheckSecretKey($request);
    
    if($secretData != ''){
      return $secretData;
    }

    $auth = Auth::user();
    $history=WatchHistory::where('user_id',$auth->id)->delete();
    if(isset($history)){
      return response()->json(array('1'), 200); 
    }
    else{
      return response()->json(array('error'), 401);       
    }
  }
  public function delete_history(Request $request,$type,$id)
  {     
    $secretData = $this->CheckSecretKey($request);
    
    if($secretData != ''){
      return $secretData;
    }

    $auth = Auth::user();
    if($type == 'T'){
      $show = WatchHistory::where('tv_id', $id)->where('user_id',$auth->id)->first();
      isset($show) ? $dshow = $show->delete() : null;
    }
    elseif($type == 'M'){
      $show = WatchHistory::where('movie_id', $id)->where('user_id',$auth->id)->first();
      isset($show) ? $dshow = $show->delete() : null;
    }
    if($dshow == 1){
      return response()->json(array('1'), 200); 
    }
    else{
      return response()->json(array('error'), 401);  
    }
  }
  public function add_history(Request $request,$type,$id){

    $secretData = $this->CheckSecretKey($request);
    
    if($secretData != ''){
      return $secretData;
    }

    $user_id = Auth::user()->id;
    if($type == 'M'){
     $movie = Movie::findOrFail($id);
      $exists = WatchHistory::where('movie_id',$id)->where('user_id',$user_id)->first();
      if (!isset($exists) && isset($movie)) {      
        $watch = WatchHistory::create([
         'movie_id'=>$id,
         'user_id'=>$user_id,
        ]);
      }
    }
    elseif($type == 'T'){   
     $tv = TvSeries::findOrFail($id);
      $exists = WatchHistory::where('tv_id',$id)->where('user_id',$user_id)->first();
      if (!isset($exists) && isset($tv)) {        
        $watch = WatchHistory::create([
         'tv_id'=>$id,
         'user_id'=>$user_id,
        ]);
      }
    }  
    if(isset($watch) || isset($exists)){
      return response()->json(array('1'), 200); 
    }
    else{
      return response()->json(array('error'), 401);  
    }
  }

 
  public function detail(Request $request,$id){

    $secretData = $this->CheckSecretKey($request);
    
    if($secretData != ''){
      return $secretData;
    }

        $filter_video = collect();
        $all_movies = Movie::where('status', '1')->get();
        $tvseries = TvSeries::where('status', '1')->get();
        $searchKey = $id;
        $actor = Actor::where('id', 'LIKE', "%$id%")->first();

        if ($searchKey != null || $searchKey != '') {
            foreach ($all_movies as $item) {
                if ($item->actor_id != null && $item->actor_id != '') {
                    $movie_actor_list = explode(',', $item->actor_id);
                    for ($i = 0; $i < count($movie_actor_list); $i++) {
                        $check = DB::table('actors')->where('id', '=', trim($movie_actor_list[$i]))->get();
                        if (isset($check[0]) && $check[0]->id == $actor->id) {
                            $filter_video->push($item);
                        }
                    }
                }
            }
        }
        
        if (isset($tvseries) && count($tvseries) > 0) {
            foreach ($tvseries as $series) {
                if (isset($series->seasons) && count($series->seasons) > 0) {
                    foreach ($series->seasons as $item) {
                        if ($item->actor_id != null && $item->actor_id != '') {
                            $season_actor_list = explode(',', $item->actor_id);
                            for ($i = 0; $i < count($season_actor_list); $i++) {
                                $check = DB::table('actors')->where('id', '=', trim($season_actor_list[$i]))->get();
                                if (isset($check[0]) && $check[0]->id == $actor->id) {
                                    $filter_video->push($item);
                                }
                            }
                        }
                    }
                }
            }
        }
        return response()->json(array('actormovies'=>$filter_video,'actor'=>$actor), 200); 
  }

  public function coupon(Request $request){

    $secretData = $this->CheckSecretKey($request);
    
    if($secretData != ''){
      return $secretData;
    }

    $coupon = CouponCode::all()->toArray();
    return response()->json(array('coupon'=>$coupon), 200);
  }

  public function verify_coupon(Request $request){

    $secretData = $this->CheckSecretKey($request);
    
    if($secretData != ''){
      return $secretData;
    }
    $validator = Validator::make($request->all(), [
      'coupon_code' => 'required',
  ]);

  if ($validator->fails()) {
       $errors = $validator->errors();

      if ($errors->first('coupon_code')) {
          return response()->json(['msg' => $errors->first('coupon_code'), 'status' => 'fail'],422);
      }
      
     
  }

    $user_id = Auth::user()->id;
    $coupon = CouponCode::where('coupon_code',$request->coupon_code)->first();
    if(isset($coupon) && $coupon != NULL){
      $current_date = Carbon::now();
      if($current_date < $coupon->redeem_by){
        if($coupon->max_redemptions != 0){

          $query = $coupon->update(['max_redemptions' => $coupon->max_redemptions - 1 ]);
          $apply_coupon = CouponApply::create([
                           'user_id'=> $user_id,
                           'coupon_id'=>$coupon->id,
                           'redeem'=> 1,
                          ]);
          $response = ["message" => "Coupon is applied !"];
          return response()->json($response,200);
        }
        else{
          $response = ["message" => "Coupon is not available !"];
          return response()->json($response, 401);
        }
      }else{
        $response = ["message" => "Coupon Expired !"];
        return response()->json($response, 401);
      }
    }else{
      $response = ["message" => "Coupon Invalid !"];
        return response()->json($response, 404);
    }
  }

  public function MovieTvByLanguage(Request $request,$id){

    $secretData = $this->CheckSecretKey($request);
    
    if($secretData != ''){
      return $secretData;
    }

    $alang = AudioLanguage::find($id);
    if (isset($alang)) {
      $moviedata = collect();
      $seasondata = collect();
      $movies = Movie::where('a_language', 'LIKE', '%' . $alang->id . '%')->where('status', 1)->with('multilinks')->get();

      foreach ($movies as $movie) {
          $moviedata->push($movie);
      }

      $tvs = Season::where('a_language', 'LIKE', '%' . $alang->id . '%')->with('episodes.multilinks')->get();

      foreach ($tvs as $tv) {
          $seasondata->push($tv);
      }
      return response()->json(array('movies' => $moviedata, 'tvseries' => $seasondata),200);
    }else{
      return response()->json(array('error'),404);
    }
  }

  public function advertise(Request $request){

    $secretData = $this->CheckSecretKey($request);
    if($secretData != ''){
      return $secretData;
    }

    $advertise = Ads::get();
    return response()->json(array('advertise' => $advertise),200);
  }


  public function CheckSecretKey($request){
    $validator = Validator::make($request->all(), [
        'secret' => 'required|string',
    ]);

    if ($validator->fails()) {
        return response()->json(['message' => 'Secret Key is required'],401);
    }

    $key = AppConfig::where('generate_apikey', '=', $request->secret)->first();

    if (!$key) {
        return response()->json(['message' => 'Invalid Secret Key !'],404);
    }
  }


  public function advPlayer(Request $request){

    $secretData = $this->CheckSecretKey($request);
    
    if($secretData != ''){
      return $secretData;
    }

    $advPlayer = Ads::get();
        
    return response()->json(array('advPlayer'=>$advPlayer), 200);       
  }


  public function audio(Request $request){

    $secretData = $this->CheckSecretKey($request);
    
    if($secretData != ''){
      return $secretData;
    }
    $auido = Audio::get();
        
    return response()->json(array('auido'=>$auido), 200);       
  }


  public function liveEvent(Request $request){

    $secretData = $this->CheckSecretKey($request);
    
    if($secretData != ''){
      return $secretData;
    }//add conditions
    $liveEvent =LiveEvent::where('status', '1')->get();
        
    return response()->json(array('liveEvent'=>$liveEvent), 200);       
  }


  public function countView(Request $request){

    $secretData = $this->CheckSecretKey($request);
    
    if($secretData != ''){
      return $secretData;
    }

    $countView = countView::get();
        
    return response()->json(array('liveEvent'=>$liveEvent), 200);       
  }
  
  public function customPage(Request $request){

    $secretData = $this->CheckSecretKey($request);
    
    if($secretData != ''){
      return $secretData;
    }

    $customPage = CustomPage::where('is_active', '1')->get();
        
    return response()->json(array('customPage'=>$customPage), 200);       
  }

  public function filter(Request $request, $menuid, $menuname)
    {

        $menu = Menu::with(['menu_data'])->findOrFail($menuid);

        $movies_ids = $menu->menu_data->pluck('movie_id')->all();

        $tv_series_ids = $menu->menu_data->pluck('tv_series_id')->all();

        $movies_ids = array_filter($movies_ids);

        $tv_series_ids = array_filter($tv_series_ids);

        $m = Movie::query();

        $tv = Tvseries::query();

        $movies = $m->wherein('id', $movies_ids)->with('video_link')->where('status', 1);

        $series = $tv->wherein('id', $tv_series_ids)->where('status', 1)
            ->whereHas('seasons_first')
            ->with(['seasons_first', 'seasons_first.firstEpisode', 'seasons_first.firstEpisode.video_link']);

        if ($request->age_rating != null) {
            if ($request->age_rating != "all") {

                $age = $request->age_rating . '+';

                $movies = $m->where('maturity_rating', '>=', $age);

                $series = $tv->where('maturity_rating', '>=', $age);
            }
        }

        if ($request->feature) {

            $movies = $m->where('featured', '=', 1);

            $series = $tv->where('featured', '=', 1);

        }

        if ($request->title != null) {

            $movies = $m->orderBy('title', $request->title);

            $series = $tv->orderBy('title', $request->title);

        }

        $movies = $m->with('menus')->get()->toArray();

        $series = $tv->with('menus')->get()->toArray();

        $finaldata = collect(array_merge_recursive($series, $movies));

        if ($request->genre != null) {
            $finaldata = $finaldata->map(function ($q) use ($request) {
                foreach ($request->genre as $generid) {

                    if (isset($q['genre_id']) && in_array($generid, explode(',', $q['genre_id']))) {
                        return $q;
                    }

                }
            });
        }


        $age = 0;

        if ($this->configs->age_restriction == 1) {
            if (Auth::user()) {
                # code...
                $user_id = Auth::user()->id;
                $user = User::findOrfail($user_id);
                $age = $user->age;
            } else {
                $age = 100;
            }
        }
        return response()->json(array('pusheditems' => $finaldata, 'menu' => $menu, 'age' => $age), 200); 

    }

  

  public function language(Request $request){

    $secretData = $this->CheckSecretKey($request);
    
    if($secretData != ''){
      return $secretData;
    }

    $language = Language::get();
        
    return response()->json(array('language'=>$language), 200);       
  }

  public function alllanguage(Request $request){

    $secretData = $this->CheckSecretKey($request);
    
    if($secretData != ''){
      return $secretData;
    }

    $language = Language::get();
        
    return response()->json(array('language'=>$language), 200);       
  }


  public function reminderSubscription(Request $request){

    $secretData = $this->CheckSecretKey($request);
    
    if($secretData != ''){
      return $secretData;
    }
    
    $reminderSubscription = ReminderMail::get();
        
    return response()->json(array('reminderSubscription'=>$reminderSubscription), 200);       
  }

  public function languageTranslator(Request $request){

    $secretData = $this->CheckSecretKey($request);
    
    if($secretData != ''){
      return $secretData;
    }

    $languageTranslator = Language::get();
        
    return response()->json(array('languageTranslator'=>$languageTranslator), 200);       
  }

  public function wallet(Request $request){

    $secretData = $this->CheckSecretKey($request);
    
    if($secretData != ''){
      return $secretData;
    }

    $wallet = UserWalletHistory::with('wallet',)->get();
    $user = User::get();    
    return response()->json(array('wallet'=>$wallet, 'user'=>$user), 200);       
  }

  public function affilate(Request $request){

    $secretData = $this->CheckSecretKey($request);
    
    if($secretData != ''){
      return $secretData;
    }

    $affilate = AffilateHistory::with('fromRefered', 'user')->get();

    return response()->json(array('affilate'=>$affilate), 200);       
  }

  
  public function topRated(Request $request, $menu_slug)
  {
    $secretData = $this->CheckSecretKey($request);
    
    if($secretData != ''){
      return $secretData;
    }

    $auth = Auth::user();
    $subscribed = null;
    if (isset($auth)) {
        $current_date = date("d/m/y");
        if ($auth->is_admin == 1 || $auth->is_assistant == 1) {
            $subscribed = 1;

        } else {
            if ($auth->stripe_id != null) {
                $customer = Customer::retrieve($auth->stripe_id);
            }
            $paypal = $auth
                ->paypal_subscriptions
                ->sortBy('created_at');
            $plans = Package::all();
            $current_date = Carbon::now()->toDateString();
            if (isset($customer)) {

                $alldata = $auth->subscriptions;
                $data = $alldata->last();
            }
            if (isset($paypal) && $paypal != null && count($paypal) > 0) {
                $last = $paypal->last();
            }
            $stripedate = isset($data) ? $data->created_at : null;
            $paydate = isset($last) ? $last->created_at : null;
            if ($stripedate > $paydate) {
                if ($auth->subscribed($data->name)) {
                    $subscribed = 1;
                }
            } elseif ($stripedate < $paydate) {
                if (date($current_date) <= date($last->subscription_to)) {
                    $subscribed = 1;
                }
            }
        }
    }

    $subscribe = $menu = Menu::whereSlug($menu_slug)->first();
    $withlogin = $this->configs->withlogin;
    //Slider get limit here and Front Slider order


    $top_data = Menu::whereSlug($menu_slug)
        ->whereHas('menu_data')
        ->whereHas('menusections')
        ->whereHas('menu_data.movie')
        ->orWhereHas('menu_data.tvseries')
        ->with(['menu_data', 'menu_data.movie', 'menu_data.tvseries', 'menu_data.tvseries.seasons',
        ])->first();
          return response()->json(array('withlogin'=>$withlogin,
         'menu'=>$menu, 'subscribed'=>$subscribed,'top_data'=>$top_data), 200); 
      

  }
  
  public function showallalang(Request $request, $id)
    {
      $secretData = $this->CheckSecretKey($request);
    
    if($secretData != ''){
      return $secretData;
    }

        $alang = AudioLanguage::find($id);

        if (isset($alang)) {
            $items = collect();
            $movies = Movie::where('a_language', 'LIKE', '%' . $alang->id . '%')->where('status', 1)->get();

            foreach ($movies as $movie) {
                $items->push($movie);
            }

            $tvs = Season::where('a_language', 'LIKE', '%' . $alang->id . '%')->get();

            foreach ($tvs as $tv) {
                $items->push($tv);
            }

            // Get current page form url e.x. &page=1
            $currentPage = LengthAwarePaginator::resolveCurrentPage();

            $itemCollection = collect($items);

            // Define how many items we want to be visible in each page
            $perPage = 15;

            // Slice the collection to get the items to display in current page
            $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();

            // Create our paginator and pass it to the view
            $paginatedItems = new LengthAwarePaginator($currentPageItems, count($itemCollection), $perPage);

            // set url path for generted links
            $paginatedItems->setPath($request->url());

            $age = 0;
            if ($this->configs->age_restriction == 1) {
                if (Auth::user()) {
                    # code...
                    $user_id = Auth::user()->id;
                    $user = User::find($user_id);
                    $age = $user->age;
                } else {
                    $age = 100;
                }
            }

            return response()->json(array('pusheditems' => $paginatedItems,  'alang' => $alang, 'age' => $age), 200); 
        } else {
          return response()->json('error', 400);
        }
    }
  
    public function appUiShorting(Request $request)
    {
      $secretData = $this->CheckSecretKey($request);
    
    if($secretData != ''){
      return $secretData;
    }

        $appUiShorting = AppUiShorting::get();
       // echo $appUiShorting;
       $appUiShorting = AppUiShorting::select('id', 'name','position','is_active')->OrderBy('position', 'ASC')->where('is_active',1)->get();

        return response()->json(array('appUiShorting'=>$appUiShorting), 200); 
    }

    public function view(Request $request)
    {
      $secretData = $this->CheckSecretKey($request);
    
    if($secretData != ''){
      return $secretData;
    }

    $view = DB::table('views')->get();
    
    return response()->json(array('view'=>$view), 200); 
    }
    

    
    public function currency(Request $request){

      $secretData = $this->CheckSecretKey($request);

      if($secretData != ''){
        return $secretData;
      }
      
      $currency = currency()->getCurrencies();
  
      return response()->json(array('currency'=>$currency), 200);       
    }

    public function switchCurrency($currency){
      Session::put('current_currency', $currency);
      return $currency;
    }


    public function hideForMe(Request $request)
    {
      $secretData = $this->CheckSecretKey($request);

      if($secretData != ''){
        return $secretData;
      }
      //  return $request;
      $userid= auth()->user()->id;
      $profile =getprofile(); 
      $dataProfile [] =  $profile;

      $exists = HideForMe::where('user_id',$userid)->where('type',$request->type)->where('movie_id',$request->id)->orwhere('season_id',$request->id)->first();
      
      if(isset($exists)){
       
        if(is_array($exists->profile)){
         
          $marks = $exists->profile;
          if(in_array($profile,$marks)){
            if(count($marks) > 1){
              // return 'grater than 1';
              $array_search_value = array_search($profile,$marks);
              unset($marks[$array_search_value]);
  
              $marks = array_values($marks);
              $exists->profile = $marks;
              $exists->save();
            }else{
              // return '1';
              $exists->delete();
            }
           
          }else{

            $exists->profile = array_merge($dataProfile,$marks);
            $exists->save();
          }
          
          //return back()->with('updated','Data updated hide successfully !');
          return response()->json(['msg' => 'Data updated hide successfully !']);
        }else{
          
          $exists->profile = $dataProfile;
          $exists->save();
          return response()->json(['msg' => 'Data hide successfully !']);
        }
      
       
      }
      else{
        if($request->type == 'M'){
          $movieid = $request->id;
          $season_id = NULL;
        }else{
          $movieid = NULL;
          $season_id =$request->id;
        }
      
        $data = HideForMe::create([
              'user_id' => $userid,
              'type' => $request->type,
              'movie_id'=>$movieid,
              'season_id' => $season_id,
              'profile' => $dataProfile,
              'created_at' => date('Y-m-d h:i:s'),
              'updated_at' => date('Y-m-d h:i:s'),
          
            ]);

        if(isset($data)){
          return response()->json(['msg' => 'Data hide successfully !']);
        }else{
         return response()->json(['msg' => 'error']);
        }
      }
    }


    public function ipblock(Request $request){

      $secretData = $this->CheckSecretKey($request);

      if($secretData != ''){
        return $secretData;
      }
      
      $ip_block = Button::select('ip_block','block_ips')->get(); 
  
      return response()->json(array('ip_block'=>$ip_block), 200);       
    }

    public function geoloaction(Request $request){

      $secretData = $this->CheckSecretKey($request);

      if($secretData != ''){
        return $secretData;
      }
      
      $geomovie = Movie::select('country')->get();
      $geotv = TvSeries::select('country')->get(); 
  
      return response()->json(array('geomovie'=>$geomovie, 'geotv'=>$geotv), 200);       
    }

    public function banneradd(Request $request){

      $secretData = $this->CheckSecretKey($request);

      if($secretData != ''){
        return $secretData;
      }
      
      $banneradd = BannerAdd::get(); 
  
      return response()->json(array('banneradd'=>$banneradd), 200);       
    }

    
  public function subscribed(Request $request){
    $secretData = $this->CheckSecretKey($request);

      if($secretData != ''){
        return $secretData;
      }

    $subscribed = 0;
    $config = \App\Config::first();
    $auth = auth()->user();
    $nav_menus = Menu::query();
    $package_menu = PackageMenu::query();
    \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
    if (isset($auth)) {

        $current_date = Illuminate\Support\Carbon::now();
        $paypal = App\PaypalSubscription::where('user_id', $auth->id)->orderBy('created_at', 'desc')->first();
        if (isset($paypal)) {

            if (date($current_date) <= date($paypal->subscription_to)) {

                if ($paypal->package_id == 0) {
                    $nav_menus = $nav_menus->get();

                    return response()->json([
                        'subs_type' => 'all_menu',
                        'nav_menus' => $nav_menus,
                        'subscribed' => true,
                        'status' => 'OK',
                    ]);

                }
            }
        }
        if ($auth->is_admin == 1 || $auth->is_assistant == 1) {

            $nav_menus = $nav_menus->orderBy('position', 'ASC')->get();
            return response()->json([
                'subs_type' => 'all_menu',
                'nav_menus' => $nav_menus,
                'subscribed' => true,
                'status' => 'OK',
            ]);

        } else {

            /** Stripe Subscription start */

            Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            if ($auth->stripe_id != null) {
                $customer = \Laravel\Cashier\Cashier::findBillable($auth->stripe_id);
                // $customer = Stripe\Customer::retrieve($auth->stripe_id);
                if (isset($customer)) {
                    $data = $auth->subscriptions->last();
                }
            }
            if (isset($paypal) && $paypal != null && $paypal->count() > 0) {
                $last = $paypal;
            }
            $stripedate = isset($data) ? $data->created_at : null;
            $paydate = isset($last) ? $last->created_at : null;
            if ($stripedate > $paydate) {

                if ($auth->subscribed($data->name) && date($current_date) <= date($data->subscription_to) && getPlan() == 1) {
                    if (isset($data->stripe_plan) && $data->stripe_plan != null) {
                        $planmenus = $package_menu->where('package_id', $data->stripe_plan)->get();

                        if (count($planmenus)) {
                            /** @return specfic plan menus */

                            $nav_menus = $nav_menus->whereIn('id', $planmenus->pluck('menu_id'))->get();
                            return response()->json([
                                'subs_type' => 'single_menu',
                                'nav_menus' => $nav_menus,
                                'subscribed' => true,
                                'status' => 'OK',
                            ]);

                        } else {
                            /** If pkg has no menu selected @return all menu */

                            $nav_menus = $nav_menus->orderBy('position', 'ASC')->get();
                            return response()->json([
                                'subs_type' => 'all_menu',
                                'nav_menus' => $nav_menus,
                                'subscribed' => true,
                                'status' => 'OK',
                            ]);
                        }

                    }
                } else {

                    return response()->json([
                        'subs_type' => 'all_menu',
                        'nav_menus' => $nav_menus,
                        'subscribed' => false,
                        'status' => 'FAIL',
                    ]);
                }
            } elseif ($stripedate < $paydate) {

                if ((date($current_date) <= date($last->subscription_to)) && $last->status == 1) {

                    if (isset($last->plan['plan_id']) && $last->plan['plan_id'] != null) {

                        $planmenus = $package_menu->where('package_id', $last->plan['plan_id'])->get();

                        if (count($planmenus)) {

                            /** @return specfic plan menus */

                            $nav_menus = $nav_menus->whereIn('id', $planmenus->pluck('menu_id'))->get();

                            return response()->json([
                                'subs_type' => 'single_menu',
                                'nav_menus' => $nav_menus,
                                'subscribed' => true,
                                'status' => 'OK',
                            ]);

                        } else {
                            /** If pkg has no menu selected @return all menu */
                            $nav_menus = $nav_menus->orderBy('position', 'ASC')->get();
                            return response()->json([
                                'subs_type' => 'all_menu',
                                'nav_menus' => $nav_menus,
                                'subscribed' => true,
                                'status' => 'OK',
                            ]);
                        }

                    }
                    else{
                        if($config->catlog == 0){
                            return response()->json([
                                // 'subs_type' => 'all_menu',
                                // 'nav_menus' => $nav_menus,
                                'subscribed' => false,
                                'status' => 'FAIL',
                            ]);
                        }else{
                            return response()->json([
                                'subs_type' => 'all_menu',
                                'nav_menus' => $nav_menus,
                                'subscribed' => false,
                                'status' => 'FAIL',
                            ]);
                        }
                    }

                } else {

                    return response()->json([
                        'subs_type' => 'all_menu',
                        'nav_menus' => $nav_menus,
                        'subscribed' => false,
                        'status' => 'FAIL',
                    ]);
                }
            } else {

                return response()->json([
                    'subs_type' => 'all_menu',
                    'nav_menus' => $nav_menus,
                    'subscribed' => false,
                    'status' => 'FAIL',
                ]);
            }
        }
    } else {
        return response()->json([
            'subscribed' => false,
            'status' => 'FAIL',
        ]);
    }
    }
  

}
