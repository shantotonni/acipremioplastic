<?php

namespace App\Http\Controllers;

use App\HappyCustomer;
use App\Area;
use Image;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class HappyCustomerController extends Controller
{

    public function index()
    {
        $happy_customer = HappyCustomer::latest()->paginate(10);
        return view('happy_customer.list',compact('happy_customer'));
    }

    public function create()
    {
        $areas = Area::all();
        return view("happy_customer.create",compact('areas'));
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            "area_id"=>"required|numeric|exists:areas,id",
            "video_url"=>"required",
        ]);

        $customer                    = new HappyCustomer();
        $customer->customer_name     = $request->customer_name;
        $customer->customer_mobile   = $request->customer_mobile;
        $customer->address           = $request->address;
        $customer->video_url           = $request->video_url;
        $customer->area_id           = $request->area_id;
        if ($request->hasFile('thumbnail_image')) {
            $image       = $request->file('thumbnail_image');
            $filename    = time().".". $image->getClientOriginalExtension();
            $image_resize = Image::make($image->getRealPath());
            $image_resize->save(public_path('/thumbnail_image/' .$filename));
            $customer->thumbnail_image = $filename;
        }
        $customer->save();

        Session::flash("success", "Happy Customer Created Successfully !");
        return redirect()->route('happy-customer.index');
    }

    public function show(HappyCustomer $happyCustomer)
    {
        //
    }

    public function edit(HappyCustomer $happyCustomer)
    {
        $happy_customer = $happyCustomer;
        $areas = Area::all();
        return view('happy_customer.edit',compact('happy_customer','areas'));
    }

    public function update(Request $request, HappyCustomer $happyCustomer)
    {
        $this->validate($request,[
            "area_id"=>"required|numeric|exists:areas,id",
            "video_url"=>"required",
        ]);

        $happyCustomer->customer_name     = $request->customer_name;
        $happyCustomer->customer_mobile   = $request->customer_mobile;
        $happyCustomer->address           = $request->address;
        $happyCustomer->video_url           = $request->video_url;
        $happyCustomer->area_id           = $request->area_id;
        if ($request->hasFile('thumbnail_image')) {
            if(file_exists(public_path('/thumbnail_image/'.$happyCustomer->thumbnail_image))){
                if (!empty($happyCustomer->thumbnail_image)) {
                    unlink(public_path('/thumbnail_image/'.$happyCustomer->thumbnail_image));
                }
            }
            $image       = $request->file('thumbnail_image');
            $filename    = time().".". $image->getClientOriginalExtension();
            $image_resize = Image::make($image->getRealPath());
            $image_resize->save(public_path('/thumbnail_image/' .$filename));
            $happyCustomer->thumbnail_image = $filename;
        }
        $happyCustomer->save();

        Session::flash("success", "Happy Customer Created Successfully !");
        return redirect()->route('happy-customer.index');
    }

    public function destroy(HappyCustomer $happyCustomer)
    {
        $happyCustomer->delete();
        Session::flash("success", "Happy Customer Deleted Successfully !");
        return response()->json(['msg' => 'Data deleted successfully'], 200);
    }
}
