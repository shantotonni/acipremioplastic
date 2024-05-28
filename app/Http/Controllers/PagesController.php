<?php

namespace App\Http\Controllers;

use App\Model\Slider;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function contactUs(){
        return view('pages.contact_us');
    }

    public function aboutUs(){
        return view('pages.about_us');
    }

    public function shippingReturn(){
        return view('pages.shipping_return');
    }

    public function privacy(){
        return view('pages.privacy');
    }

    public function termsOfCondition(){
        return view('pages.terms_condition');
    }
    public function kidsEducational(){
        return view('pages.kids_educational');
    }

    public function howToBuy() {
        return view('pages.how_to_buy');
    } public function catalogue() {
    $project_id = config('app.project_id');
    $sliders = Slider::select('BannerID','BannerImageFile','Url')->where('Active','Y')->where('ProjectID',$project_id)->orderBy('EditedDate','desc')->get();
    return view('pages.catalogue',compact('sliders'));

    }

    public function catalogueKings() {
        return view('pages.kings_group');
    }
    public function catalogueCaptain() {
        return view('pages.captains_group');
    }

    public function deliveryPolicy() {
        return view('pages.delivery_policy');
    }

    public function refundPolicy() {
        return view('pages.refund_policy');
    }

}
