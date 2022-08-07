<?php

namespace App\Http\Controllers;

use App\Ads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class AdsController extends Controller
{
   

    public function __construct()
    {
        $this->middleware('permission:ads.view', ['only' => ['index', 'getAdsSettings']]);
        $this->middleware('permission:ads.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:ads.edit', ['only' => ['showEdit', 'edit', 'updateAd', 'updatePopAd', 'updateADSOLO', 'updateVideoAD']]);
        $this->middleware('permission:ads.delete', ['only' => ['delete', 'bulk_delete']]);
    }

    public function getAds()
    {
        return view('advertise.index');
    }

    public function create()
    {
        return view('advertise.create');
    }

    public function showEdit($id)
    {
        $ad = Ads::findorfail($id);
        return view('advertise.edit', compact('ad'));
    }

    public function store(Request $request)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        $newad = new Ads;

        if ($request->ad_location == "skip") {
            $newad->ad_type = "video";
        } else {
            $newad->ad_type = "image";
        }

        $newad->ad_location = $request->ad_location;
        $newad->ad_target = $request->ad_target;

        if ($request->ad_location == "popup" || $request->ad_location == "onpause") {
            $request->validate([
                'ad_image' => 'required|image:png,jpg,jpeg',
            ]);
            if ($file = $request->file('ad_image')) {

                $name = time() . $file->getClientOriginalName();
                $file->move('adv_upload/image', $name);
                $newad->ad_image = $name;
                $newad->ad_video = "no";

                if ($request->ad_location == "popup") {
                    $request->validate([
                        'time' => 'required',
                        'endtime' => 'required',
                    ], [
                        'time.required' => __('Please add start time'),
                        'endtime.required' => __('Please add end time'),
                    ]);
                    $newad->time = $request->time;
                    $newad->endtime = $request->endtime;
                } else {
                    $newad->time = "00:00:00";
                    $newad->endtime = "00:00:00";
                }

            }

        }

        if ($request->ad_location == "skip") {

            if ($request->checkType == "upload") {

                if ($file = $request->file('ad_video')) {

                    $request->validate([
                        'ad_video' => 'mimes:mp4,mov,ogg | max:10000',
                        'ad_hold' => 'int',
                        'time' => 'required',
                    ], [
                        'ad.hold' => __('Ad Hold time must be in valid format'),
                        'time.required' => __('Please add start time'),
                    ]);
                    $name = time() . $file->getClientOriginalName();
                    $file->move('adv_upload/video', $name);
                    $newad->ad_video = $name;
                    $newad->ad_image = "no";
                    $newad->ad_hold = $request->ad_hold;
                    $newad->time = $request->time;
                    $newad->endtime = null;
                }

            } elseif ($request->checkType == "url") {
                $request->validate([

                    'time' => 'required',
                ], [

                    'time.required' => __('Please add start time'),
                ]);
                $newad->ad_video = "no";
                $newad->ad_image = "no";
                $newad->ad_url = $request->ad_url;
                $newad->ad_hold = $request->ad_hold;
                $newad->time = $request->time;
                $newad->endtime = null;
            }

        }
        try {
            $newad->save();
            return back()->with('updated', __('Ad Created Successfully !'));
        } catch (\Exception $e) {
            return back()->with('deleted', $e->getMessage());
        }

    }

    public function getAdsSettings()
    {
        return view('advertise.adsetting');
    }

    public function updateAd(Request $request)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        if ($request->timer_check == "no") {
            $ad = DB::table('ads')->where('ad_type', 'video')->update(array('time' => '00:00:00'));
        } elseif ($request->timer_check == "yes") {
            if ($request->ad_timer != "") {
                $ad = DB::table('ads')->where('ad_type', 'video')->update(array('time' => $request->ad_timer));
            }

        }

        if ($request->ad_hold != "") {
            $request->validate([
                'ad_hold' => 'int',
            ],
                ['ad.hold' => 'Invalid format']
            );

            $ad = DB::table('ads')->where('ad_type', 'video')->update(array('ad_hold' => $request->ad_hold));
        }

        return back()->with('updated', __('Ad Settings Upated'));
    }

    public function updatePopAd(Request $request)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        if ($request->time != "") {
            $ad2 = DB::table('ads')->where('ad_location', 'popup')->update(array('time' => $request->time));
        }

        if ($request->endtime != "") {
            $ad = DB::table('ads')->where('ad_location', 'popup')->update(array('endtime' => $request->endtime));
        }

        return back()->with('updated', __('Popup Ad Setting Updated !'));
    }

    public function delete($id)
    {
        $ad = Ads::findorfail($id);
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        if ($ad->ad_type == "image") {
            unlink('adv_upload/image/' . $ad->ad_image);
            $ad->delete();
        } elseif ($ad->ad_type == "video") {
            if ($ad->ad_video != "no") {
                unlink('adv_upload/video/' . $ad->ad_video);
                $ad->delete();
            } else {
                $ad->delete();
            }
        }

        return back()->with('deleted', __('Ad Deleted Successfully !'));
    }

    public function updateADSOLO(Request $request, $id)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted',__('This action is disabled in the demo !'));
        }

        $request->validate([
            'ad_image' => 'image:png,jpg,jpeg | max:3500',
        ]);

        $ad = Ads::findorfail($id);

        $ad->ad_target = $request->ad_target;

        if ($file = $request->file('ad_image')) {

            unlink('adv_upload/image/' . $ad->ad_image);

            $name = time() . $file->getClientOriginalName();

            $file->move('adv_upload/image', $name);

            $ad->ad_image = $name;

        }

        $ad->save();

        return redirect()->route('ads')->with('updated', __('Ad Updated Successfully'));

    }

    public function updateVideoAD(Request $request, $id)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        $request->validate([
            'ad_video' => 'mimes:mp4,mov,ogg | max:10000',
        ]);

        $ad = Ads::findorfail($id);

        $ad->ad_target = $request->ad_target;

        if ($ad->ad_video == "no") {
            $ad->ad_url = $request->ad_url;
        }

        if ($file = $request->file('ad_video')) {

            unlink('adv_upload/video/' . $ad->ad_video);

            $name = time() . $file->getClientOriginalName();

            $file->move('adv_upload/video', $name);

            $ad->ad_video = $name;

        }

        $ad->save();

        return redirect()->route('ads')->with('updated', __('Ad Updated Successfully'));

    }

    public function bulk_delete(Request $request)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        $validator = Validator::make($request->all(), [
            'checked' => 'required',
        ]);

        if ($validator->fails()) {

            return back()->with('deleted', __('Please select one of them to delete'));
        }

        foreach ($request->checked as $checked) {

            $ads = Ads::findOrFail($checked);

            $ads::destroy($checked);

            if ($ads->ad_type == "image" && $ads->ad_video == "no") {
                unlink('adv_upload/image/' . $ads->ad_image);
            }

            if ($ads->ad_type == "video" && $ads->ad_image == "no" && $ads->ad_url == "") {
                unlink('adv_upload/video/' . $ads->ad_video);
            }

        }

        return back()->with('deleted', __('Ads has been deleted'));
    }
}
