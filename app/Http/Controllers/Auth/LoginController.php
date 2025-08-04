<?php

namespace App\Http\Controllers\Auth;

use App\Model\CouponLog;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    protected $redirectTo = '/customer/profile';

    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required',
            'password' => 'required',
        ]);
    }

    public function username()
    {
        return 'CustomerMobileNo';
    }

    public function login(Request $request){

        $this->validateLogin($request);

        if(Auth::attempt(['CustomerMobileNo' => $request->CustomerMobileNo, 'password' => $request->password,'ProjectID' =>config('app.project_id')])) {
             Toastr::success('Customer Successfully Login' ,'Success');
//             return redirect('/customer/profile');
            return redirect()->intended('/customer/profile');
        }  else {
            $this->incrementLoginAttempts($request);
            Toastr::error('Customer Not Active' ,'Error');
        }

        $this->incrementLoginAttempts($request);
        return $this->sendFailedLoginResponse($request);
    }

    public function loginWithOffer(Request $request){

        $project_id = config('app.project_id');
        $this->validateLogin($request);

        if(Auth::attempt(['CustomerMobileNo' => $request->CustomerMobileNo, 'password' => $request->password,'ProjectID' =>config('app.project_id')])) {
            $created_at = Carbon::now();

            $coupon_log = new CouponLog();
            $coupon_log->ProjectID = $project_id;
            $coupon_log->UserId = Auth::user()->CustomerID;
            $coupon_log->CouponCode = $request->couponCode;
            $coupon_log->Mobile = $request->CustomerMobileNo;
            $coupon_log->CouponName = $request->offer_name;
            $coupon_log->Offer = $request->offer;
            $coupon_log->OfferType = "percentage";
            $coupon_log->CreatedAt = $created_at;
            $coupon_log->UpdatedAt = $created_at;
            $coupon_log->ValidUpto = $created_at->addMinutes(30);
            $coupon_log->save();

            return response()->json(['success'=>'Login Successfully','CouponID' =>$coupon_log->CouponID]);
        }  else {
            $this->incrementLoginAttempts($request);
            Toastr::error('Customer Not Active' ,'Error');
        }

        $this->incrementLoginAttempts($request);
        return $this->sendFailedLoginResponse($request);
    }

    protected function credentials(Request $request){

        return ['CustomerMobileNo'=> $request->email,'password'=> $request->password,'ProjectID' =>config('app.project_id')];

    }

    public function redirectToProvider()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleProviderCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect('/login');
        }
        $ip = $_SERVER['REMOTE_ADDR'];
        $project_id = config('app.project_id');
        // check if they're an existing user
        $existingUser = User::where('CustomerEmail', $user->email)->where('ProjectID',$project_id)->first();

        if($existingUser){
            // log them in
            auth()->login($existingUser, true);
        } else {
            // create a new user
            $newUser                               = new User;
            $newUser->ProjectID                    = $project_id;
            $newUser->CustomerFirstName            = $user->name;
            $newUser->CustomerEmail                = $user->email;
            $newUser->google_id                    = $user->id;
            $newUser->avatar                       = $user->avatar;
            $newUser->avatar_original              = $user->avatar_original;
            $newUser->CreatedDate                  = Carbon::now()->format('Y-m-d');
            $newUser->EditedDate                   = Carbon::now()->format('Y-m-d');
            $newUser->CreatedIP                    = $ip;
            $newUser->CreatedDeviceState           = 'dhaka';
            $newUser->password                     = bcrypt('123123');
            $newUser->Status = '1';
            $newUser->save();

            auth()->login($newUser, true);
        }
        Toastr::success('Customer Successfully Login' ,'Success');
        return redirect()->route('customer.profile');
        //return redirect()->intended('/customer/profile');
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
