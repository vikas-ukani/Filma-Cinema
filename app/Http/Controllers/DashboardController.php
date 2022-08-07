<?php

namespace App\Http\Controllers;

use App\Charts\SalesChart;
use App\Charts\UserChart;
use App\Charts\UserDistributionChart;
use App\Charts\VideoDistributionChart;
use App\Charts\VisitorsChart;
use App\CouponCode;
use App\Genre;
use App\Movie;
use App\Package;
use App\PaypalSubscription;
use App\TvSeries;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class DashboardController extends Controller
{
   
    public function dashboard()
    {

        $users_count = User::count();
        $movies_count = Movie::where('live', 0)->count();
        $tvseries_count = TvSeries::count();
        $livetv_count = Movie::where('live', 1)->count();
        $genres_count = Genre::count();
        $package_count = Package::where('status', 'active')->orwhere('status', 'upcoming')->where('delete_status', 1)->count();
        $coupon_count = CouponCode::count();
        $activeusers = PaypalSubscription::join('users', 'users.id', '=', 'paypal_subscriptions.user_id')->where('paypal_subscriptions.status', '=', '1')->where('users.is_blocked', '=', 0)->where('users.status', '=', 1)->distinct()->count('paypal_subscriptions.user_id');
        $totalrevnue = PaypalSubscription::sum('price');
        $users = User::where(DB::raw("(DATE_FORMAT(created_at,'%Y'))"), date('Y'))->get();
        $activesubsriber = PaypalSubscription::where(DB::raw("(DATE_FORMAT(created_at,'%Y'))"), date('Y'))->where('status', '1')->get();
        $inactivesubsriber = PaypalSubscription::where(DB::raw("(DATE_FORMAT(created_at,'%Y'))"), date('Y'))->where('status', '0')->count();
        $subsribeuseruser = PaypalSubscription::where(DB::raw("(DATE_FORMAT(created_at,'%Y'))"), date('Y'))->count();
        $fillColors = [
            '#f44336',
            '#4CAF50',
            '#2196F3',
            '#03A9F4',
            '#00BCD4',
            '#009688',
            '#8BC34A',
            '#CDDC39',
            '#FFC107',
            '#FF9800',
            '#FF5722',
        ];

        /*Creating Userbarchart*/
        $users = array(
            User::whereMonth('created_at', '01')
                ->whereYear('created_at', date('Y'))
                ->count(), //January
            User::whereMonth('created_at', '02')
                ->whereYear('created_at', date('Y'))
                ->count(), //Feb
            User::whereMonth('created_at', '03')
                ->whereYear('created_at', date('Y'))
                ->count(), //March
            User::whereMonth('created_at', '04')
                ->whereYear('created_at', date('Y'))
                ->count(), //April
            User::whereMonth('created_at', '05')
                ->whereYear('created_at', date('Y'))
                ->count(), //May
            User::whereMonth('created_at', '06')
                ->whereYear('created_at', date('Y'))
                ->count(), //June
            User::whereMonth('created_at', '07')
                ->whereYear('created_at', date('Y'))
                ->count(), //July
            User::whereMonth('created_at', '08')
                ->whereYear('created_at', date('Y'))
                ->count(), //August
            User::whereMonth('created_at', '09')
                ->whereYear('created_at', date('Y'))
                ->count(), //September
            User::whereMonth('created_at', '10')
                ->whereYear('created_at', date('Y'))
                ->count(), //October
            User::whereMonth('created_at', '11')
                ->whereYear('created_at', date('Y'))
                ->count(), //November
            User::whereMonth('created_at', '12')
                ->whereYear('created_at', date('Y'))
                ->count(), //December
        );

        $userchart = new UserChart;
        $userchart->labels(['January', 'Febuary', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']);

        $userchart->dataset('Monthly Registered Users', 'line', $users)->options([
            'fill' => 'true',
            'shadow' => 'true',
            'borderWidth' => '1',
        ])->backgroundcolor("#f24236e3")->color('#f24236e3');
        /*END*/

        /*Creating Active subscriber chart*/

        $activesub = array(
            PaypalSubscription::whereMonth('created_at', '01')->where('status', '1')
                ->whereYear('created_at', date('Y'))
                ->distinct()->count('user_id'), //January
            PaypalSubscription::whereMonth('created_at', '02')->where('status', '1')
                ->whereYear('created_at', date('Y'))
                ->distinct()->count('user_id'), //Feb
            PaypalSubscription::whereMonth('created_at', '03')->where('status', '1')
                ->whereYear('created_at', date('Y'))
                ->distinct()->count('user_id'), //March
            PaypalSubscription::whereMonth('created_at', '04')->where('status', '1')
                ->whereYear('created_at', date('Y'))
                ->distinct()->count('user_id'), //April
            PaypalSubscription::whereMonth('created_at', '05')->where('status', '1')
                ->whereYear('created_at', date('Y'))
                ->distinct()->count('user_id'), //May
            PaypalSubscription::whereMonth('created_at', '06')->where('status', '1')
                ->whereYear('created_at', date('Y'))
                ->distinct()->count('user_id'), //June
            PaypalSubscription::whereMonth('created_at', '07')->where('status', '1')
                ->whereYear('created_at', date('Y'))
                ->distinct()->count('user_id'), //July
            PaypalSubscription::whereMonth('created_at', '08')->where('status', '1')
                ->whereYear('created_at', date('Y'))
                ->distinct()->count('user_id'), //August
            PaypalSubscription::whereMonth('created_at', '09')->where('status', '1')
                ->whereYear('created_at', date('Y'))
                ->distinct()->count('user_id'), //September
            PaypalSubscription::whereMonth('created_at', '10')->where('status', '1')
                ->whereYear('created_at', date('Y'))
                ->distinct()->count('user_id'), //October
            PaypalSubscription::whereMonth('created_at', '11')->where('status', '1')
                ->whereYear('created_at', date('Y'))
                ->distinct()->count('user_id'), //November
            PaypalSubscription::whereMonth('created_at', '12')->where('status', '1')
                ->whereYear('created_at', date('Y'))
                ->distinct()->count('user_id'), //December
        );

        $activesubsriber = new VisitorsChart;
        $activesubsriber->labels(['January', 'Febuary', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']);
        $activesubsriber->label('Active Plan Users')->dataset('Monthly Subscribed Users', 'area', $activesub)->options([
            'fill' => 'true',
            'shadow' => true,
            'borderWidth' => '1',
        ]);
        /*END*/

        $doughnutchart = new VideoDistributionChart;
        $doughnutchart->minimalist(true);
        $doughnutchart->labels(['Movies', 'Tv Seires', 'LiveTv']);
        $data = [$movies_count, $tvseries_count, $livetv_count];
        $doughnutchart->dataset('Video Distribution', 'doughnut', $data)
            ->color($fillColors)
            ->backgroundcolor($fillColors);

        $piechart = new UserDistributionChart;
        $piechart->minimalist(true);
        $piechart->labels(['Active User', 'Subscribed User', 'Inactive user']);
        $value = [$activeusers, $subsribeuseruser, $inactivesubsriber];
        $piechart->dataset('User Distribution', 'pie', $value)->options([
            'fill' => 'true',
            'shadow' => true,
        ])->color($fillColors);

        Artisan::call('inspire');
        $greetings = "";

        /* This sets the $time variable to the current hour in the 24 hour clock format */
        $time = date("H");

        /* Set the $timezone variable to become the current timezone */
        $timezone = date("e");

        /* If the time is less than 1200 hours, show good morning */
        if ($time < "12") {
            $greetings = "Good morning";
        } else

        /* If the time is grater than or equal to 1200 hours, but less than 1700 hours, so good afternoon */
        if ($time >= "12" && $time < "17") {
            $greetings = "Good afternoon";
        } else

        /* Should the time be between or equal to 1700 and 1900 hours, show good evening */
        if ($time >= "17" && $time < "19") {
            $greetings = "Good evening";
        } else

        /* Finally, show good night if the time is greater than or equal to 1900 hours */
        if ($time >= "19") {
            $greetings = "Good night";
        }

        $today = date("l . F j, Y . g:i:s A . T");

        $latest_users = User::where('is_admin', '!=', '1')->orderBy('id', 'DESC')->take(8)->get();

        $y = date('Y');

        $stardate = date('Y-m-d', strtotime($y . '-01-01'));

        $enddate = date('Y-m-d', strtotime($y . '-12-31'));

        $revenue_chart = new SalesChart;

        $revenue_chart->labels(['January', 'Febuary', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']);

        $revenue_report = PaypalSubscription::whereBetween('subscription_from', [$stardate, $enddate])->get();

        $chartData = array(
            PaypalSubscription::whereMonth('created_at', '01')
                ->whereYear('created_at', date('Y'))
                ->count(), //January
            PaypalSubscription::whereMonth('created_at', '02')
                ->whereYear('created_at', date('Y'))
                ->count(), //Feb
            PaypalSubscription::whereMonth('created_at', '03')
                ->whereYear('created_at', date('Y'))
                ->count(), //March
            PaypalSubscription::whereMonth('created_at', '04')
                ->whereYear('created_at', date('Y'))
                ->count(), //April
            PaypalSubscription::whereMonth('created_at', '05')
                ->whereYear('created_at', date('Y'))
                ->count(), //May
            PaypalSubscription::whereMonth('created_at', '06')
                ->whereYear('created_at', date('Y'))
                ->count(), //June
            PaypalSubscription::whereMonth('created_at', '07')
                ->whereYear('created_at', date('Y'))
                ->count(), //July
            PaypalSubscription::whereMonth('created_at', '08')
                ->whereYear('created_at', date('Y'))
                ->count(), //August
            PaypalSubscription::whereMonth('created_at', '09')
                ->whereYear('created_at', date('Y'))
                ->count(), //September
            PaypalSubscription::whereMonth('created_at', '10')
                ->whereYear('created_at', date('Y'))
                ->count(), //October
            PaypalSubscription::whereMonth('created_at', '11')
                ->whereYear('created_at', date('Y'))
                ->count(), //November
            PaypalSubscription::whereMonth('created_at', '12')
                ->whereYear('created_at', date('Y'))
                ->count(), //December
        );

        $borderColors = [
            "rgba(255, 99, 132,1)",
            "rgba(22,160,133, 1)",
            "rgba(255, 205, 86, 1)",
            "rgba(51,105,232, 1)",
            "rgba(244,67,54, 1)",
            "rgba(34,198,246, 1)",
            "rgba(153, 102, 255, 1)",
            "rgba(255, 159, 64,1)",
            "rgba(233,30,99, 1)",
            "rgba(205,220,57,1)",
        ];

        $revenue_chart->title('Total paypal subscription revenue')->dataset('Paypal subscription revenue', 'bar', $chartData)->options([
            'fill' => 'true',
            'shadow' => 'true',
            'borderWidth' => '1',
        ])->backgroundColor($borderColors);

        $revenue_report = PaypalSubscription::whereBetween('subscription_from', [$stardate, $enddate])->orderBy('id', 'DESC')->take(10)->get();
        return view('admin.index', compact('genres_count', 'users_count', 'movies_count', 'tvseries_count', 'package_count', 'coupon_count', 'activeusers', 'totalrevnue', 'userchart', 'activesubsriber', 'livetv_count', 'piechart', 'doughnutchart', 'greetings', 'today', 'latest_users', 'revenue_chart', 'revenue_report'));
    }

    public function device_history(Request $request)
    {
        $users = DB::table('users')
            ->join('auth_log', 'users.id', '=', 'auth_log.authenticatable_id')
            ->select('users.name as username', 'users.email as useremail', 'auth_log.*');

        if (request()->ajax()) {
            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('username', function ($row) {

                    $html = '<b> <span class="text-dark">Name:</span> </b>' . $row->username;
                    $html .= '<br>';
                    $html .= '<b> <span class="text-dark">Email:</span> </b>' . $row->useremail;
                    return $html;

                })
                ->addColumn('ip_address', function ($row) {
                    return $row->ip_address;
                })
                ->addColumn('platform', function ($row) {
                    return $row->platform;
                })
                ->addColumn('browser', function ($row) {
                    return $row->browser;
                })
                ->addColumn('login_at', function ($row) {
                    return $row->login_at ? date('d-m-Y | h:i A', strtotime($row->login_at)) : '-';
                })
                ->addColumn('logout_at', function ($row) {
                    return $row->logout_at ? date('d-m-Y | h:i A', strtotime($row->logout_at)) : '-';
                })
                ->rawColumns(['username', 'ip_address', 'platform', 'browser', 'login_at', 'logout_at'])
                ->make(true);
        }

        return view('admin.device-history');
    }
}
