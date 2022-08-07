<?php

namespace App\Http\Controllers;

use App\Charts\SalesChart;
use App\PaypalSubscription;
use Illuminate\Http\Request;
use Stripe\Subscription;
use \Stripe\Stripe;


class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:reports.user-subscription', ['only' => ['get_report']]);
        $this->middleware('permission:reports.revenue', ['only' => ['get_revenue_report']]);

    }
    public function get_report()
    {
        // Set your secret key: remember to change this to your live secret key in production
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $all_reports = Subscription::all();
        $paypal_subscriptions = PaypalSubscription::all();
        $sells = $paypal_subscriptions->sum('price');
        return view('admin.report.index', compact('all_reports', 'paypal_subscriptions', 'sells'));
    }

    public function get_revenue_report(Request $request)
    {
        $y = date('Y');

        $stardate = date('Y-m-d', strtotime($y . '-01-01'));

        $enddate = date('Y-m-d', strtotime($y . '-12-31'));

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
        $fillColors = [
            "rgba(255, 99, 132, 0.2)",
            "rgba(22,160,133, 0.2)",
            "rgba(255, 205, 86, 0.2)",
            "rgba(51,105,232, 0.2)",
            "rgba(244,67,54, 0.2)",
            "rgba(34,198,246, 0.2)",
            "rgba(153, 102, 255, 0.2)",
            "rgba(255, 159, 64, 0.2)",
            "rgba(233,30,99, 0.2)",
            "rgba(205,220,57, 0.2)",
        ];

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

        $revenue_chart->title('Total paypal subscription revenue')->dataset('Paypal subscription revenue', 'bar', $chartData)->options([
            'fill' => 'true',
            'shadow' => 'true',
            'borderWidth' => '1',
        ])->backgroundColor($fillColors)->color($borderColors);

        return view('admin.report.revenue', compact('revenue_report', 'revenue_chart'));
    }

}
