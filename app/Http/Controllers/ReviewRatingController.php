<?php

namespace App\Http\Controllers;

use App\Model\ReviewRating;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewRatingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function storeReviewRating(Request $request){

        $review            = new ReviewRating();
        $review->ProductId = $request->product_id;
        $review->UserId   = Auth::user()->CustomerID;
        $review->Rating    = $request->rating;
        $review->Comment   = $request->comment;
        $review->Approved  = 1;
        $review->CreatedAt  = Carbon::now()->format('Y-m-d');
        $review->save();
        Toastr::success('Successfully Done' ,'Success');
        return redirect()->back();
    }

}
