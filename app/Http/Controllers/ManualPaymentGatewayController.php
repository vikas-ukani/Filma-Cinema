<?php

namespace App\Http\Controllers;

use App\ManualPaymentMethod;
use Illuminate\Http\Request;
use Image;


class ManualPaymentGatewayController extends Controller
{
  

    public function __construct()
    {
        $this->middleware('permission:manual-payment.view', ['only' => ['getindex']]);
        $this->middleware('permission:manual-payment.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:manual-payment.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:manual-payment.delete', ['only' => ['destroy']]);
    }
    public function getindex()
    {

        $methods = ManualPaymentMethod::orderBy('id', 'DESC')->get();
        return view('admin.manualpayment.index', compact('methods'));
    }

    public function store(Request $request)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }

        $request->validate([
            'payment_name' => 'required|string|max:50|unique:manual_payment_methods,payment_name',
            'description' => 'required|max:5000',
            'thumbnail' => 'mimes:jpg,jpeg,png,webp,bmp',
        ]);

        $newmethod = new ManualPaymentMethod;
        $input = $request->all();

        if (!is_dir(public_path() . '/images/manual_payment')) {
            mkdir(public_path() . '/images/manual_payment');
        }

        if ($request->file('thumbnail')) {

            $image = $request->file('thumbnail');
            $img = Image::make($image->path());
            $mp = 'mp_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/images/manualpayment');
            $img->resize(600, 600, function ($constraint) {
                $constraint->aspectRatio();
            });

            $img->save($destinationPath . '/' . $mp);
            $input['thumbnail'] = $mp;
        }

        $input['status'] = isset($request->status) ? 1 : 0;

        $newmethod->create($input);

        notify()->success('Payment method added !', $request->payment_name);
        return back();

    }

    public function update(Request $request, $id)
    {
        return $request;
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }

        $method = ManualPaymentMethod::find($id);

        if (!$method) {

            return back()->with('deleted', __('Payment method not found!'));
        }

        $request->validate([
            'payment_name' => 'required|string|max:50|unique:manual_payment_methods,payment_name,' . $method->id,
            'description' => 'required|max:5000',
            'thumbnail' => 'mimes:jpg,jpeg,png,webp,bmp',
        ]);

        $input = $request->all();

        if ($request->file('thumbnail')) {

            if (!is_dir(public_path() . '/images/manualpayment')) {
                mkdir(public_path() . '/images/manualpayment');
            }

            $image = $request->file('thumbnail');
            $img = Image::make($image->path());

            if ($method->thumbnail != '' && file_exists(public_path() . '/images/manualpayment/' . $method->thumbnail)) {
                unlink(public_path() . '/images/manualpayment/' . $method->thumbnail);
            }

            $mp = 'mp_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/images/manualpayment');
            $img->resize(600, 600, function ($constraint) {
                $constraint->aspectRatio();
            });

            $img->save($destinationPath . '/' . $mp);
            $input['thumbnail'] = $mp;
        }

        $input['status'] = isset($request->status) ? 1 : 0;

        $method->update($input);

        return back()->with('deleted', __('Payment method update!'));

    }

    public function delete($id)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        $method = ManualPaymentMethod::find($id);

        if (!$method) {

            return back()->with('deleted', __('Payment method not found!'));
        }

        if ($method->thumbnail != '' && file_exists(public_path() . '/images/manualpayment/' . $method->thumbnail)) {
            unlink(public_path() . '/images/manualpayment/' . $method->thumbnail);
        }

        $method->delete();

        return back()->with('deleted', __('Payment method deleted!'));
    }
}
