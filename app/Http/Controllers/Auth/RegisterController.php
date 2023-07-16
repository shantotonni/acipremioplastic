<?php

namespace App\Http\Controllers\Auth;

use App\Model\CouponLog;
use App\Model\OtpGenaration;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    protected $redirectTo = '/customer/profile';

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function sendOtp(Request $request){

        $validator = Validator::make($request->all(), [
            'phone' => 'required|numeric|digits:11',
        ]);

//        $exists = User::where('CustomerMobileNo',$request->phone)->where('ProjectID',config('app.project_id'))->exists();
//
//        if ($exists){
//            return response()->json(['error'=>'Already Exist This number']);
//        }

        if ($validator->passes()) {

            $unique_code = mt_rand(1000,9999);

            $otp =new OtpGenaration();
            $otp->MobileNo = $request->phone;
            $otp->OTP = $unique_code;
            $otp->OTPTime = Carbon::now()->format('Y-m-d');
            $otp->OTPUsed = '0';
            $otp->GenerateModule = 'otp';

            if ($otp->save()){
                $smstext="Your Otp Code ".$unique_code;
                $peram = $this->sendSms($request->phone, $smstext);
                if($peram->isError===false){
                    return response()->json(['success'=>'OTP send Successfully.Please Check Your Mobile']);
                }else{
                    return response()->json(['error'=>'Something went wrong']);
                }
            }

            return response()->json(['error'=>'Something went wrong']);
        }

        return response()->json(['error'=>$validator->errors()->all()]);
    }

    function sendSms($receipient, $smstext) {
        $ip = "192.168.100.213";
        $userId = "cepl";
        $password = "Asdf1234";
        $smstext = urlencode($smstext);
        $smsUrl = "http://{$ip}/httpapi/sendsms?userId={$userId}&password={$password}&smsText=" . $smstext . "&commaSeperatedReceiverNumbers=" . $receipient;
        $smsUrl = preg_replace("/ /", "%20", $smsUrl);
        $response = file_get_contents($smsUrl);
        return json_decode($response);
    }

    public function sendOtpForForgot(Request $request){

        $validator = Validator::make($request->all(), [
            'phone' => 'required|numeric|digits:11',
        ]);

        if ($validator->passes()) {

            $exists = User::where('CustomerMobileNo',$request->phone)->where('ProjectID',config('app.project_id'))->exists();

            if ($exists){
                $otp =new OtpGenaration();
                $otp->MobileNo = $request->phone;
                $otp->OTP = '1234';
                $otp->OTPTime = Carbon::now()->format('Y-m-d');
                $otp->OTPUsed = '0';
                $otp->GenerateModule = 'otp';

                if ($otp->save()){
                    return response()->json(['success'=>'OTP send Successfully.']);
                }

                return response()->json(['error'=>'Something went wrong']);
            }else{
                return response()->json(['error'=>'Mobile number Not Found']);
            }

        }

        return response()->json(['error'=>$validator->errors()->all()]);
    }

    public function forgotPasswordChange(Request $request){

        $validator = Validator::make($request->all(), [
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        if ($validator->passes()) {

            $exists = User::where('CustomerMobileNo',$request->phone_number)->where('ProjectID',config('app.project_id'))->exists();

            if ($exists){
                $customer = User::where('CustomerMobileNo',$request->phone_number)->first();
                $customer->password = bcrypt($request->password);
                $customer->save();
                return response()->json(['success'=>'Password Change successfully :)']);
            }else{
                return response()->json(['error'=>'Mobile number Not Found']);
            }

        }

        return response()->json(['error'=>$validator->errors()->all()]);

    }

    public function otpVerify(Request $request){

        $validator = Validator::make($request->all(), [
            'phone' => 'required|numeric|digits:11',
        ]);

        if ($validator->passes()) {

            $otp = OtpGenaration::where('MobileNo',$request->phone)->where('OTP',$request->otp_number)->where('OTPUsed',0)->orderBy('OTPId','desc')->first();

            if (isset($otp) && !empty($otp)){
                $otp->OTPUsed = '1';
                $otp->save();
                return response()->json(['success'=>'OTP Verified Successfully.']);
            }
            return response()->json(['error'=>'Something went wrong']);
        }

        return response()->json(['error'=>$validator->errors()->all()]);
    }

//    protected function validator(array $data)
//    {
//         $validator = Validator::make($data, [
//            'FirstName' => ['required', 'string', 'max:255'],
//            'LastName' => ['required', 'string', 'max:255'],
//            'phone' => 'required|numeric|digits:11',
//            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
//            'password' => ['required', 'string', 'min:8', 'confirmed'],
//        ]);
//
//        if ($validator->passes()) {
//            dd('ok');
//            return $validator;
//        }
//        return $validator;
//        //return response()->json(['error'=>$validator->errors()->all()]);
//    }
//
//    protected function create(array $data)
//    {
//        return User::create([
//            'name' => $data['name'],
//            'email' => $data['email'],
//            'password' => Hash::make($data['password']),
//        ]);
//    }


    public function register(Request $request)  {

        $validator = Validator::make($request->all(), [
            'CustomerFirstName' => ['required', 'string', 'max:255'],
            'CustomerLastName' => ['required', 'string', 'max:255'],
            'CustomerMobileNo' => ['required','numeric','digits:11'],
            'CustomerEmail' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $ip = $_SERVER['REMOTE_ADDR'];
        $project_id = config('app.project_id');

        if ($validator->passes()) {
            $customer = new User();
            $customer->ProjectID = $project_id;
            $customer->CustomerFirstName = $request->CustomerFirstName;
            $customer->CustomerLastName = $request->CustomerLastName;
            $customer->CustomerMobileNo = $request->CustomerMobileNo;
            $customer->CustomerEmail = $request->CustomerEmail;
            $customer->CreatedDate = Carbon::now()->format('Y-m-d');
            $customer->EditedDate = Carbon::now()->format('Y-m-d');
            $customer->CreatedIP = $ip;
            $customer->CreatedDeviceState = 'dhaka';
            $customer->password = bcrypt($request->password);
            $customer->Status = '1';
            if ($customer->save()){
                ///customer/profile
                if(Auth::attempt(['CustomerMobileNo' => $request->CustomerMobileNo, 'password' => $request->password,'ProjectID' =>config('app.project_id')])) {
                    return response()->json(['success'=>'Registration Successfully Generated']);
                }  else {
                    $this->incrementLoginAttempts($request);
                    return response()->json(['error'=>'Customer Not Active']);
                }

                $this->incrementLoginAttempts($request);
                return $this->sendFailedLoginResponse($request);
            }
            return response()->json(['error'=>'Something went wrong']);
        }

        return response()->json(['error'=>$validator->errors()->all()]);
    }


    public function registrationWithOffer(Request $request)  {

        $validator = Validator::make($request->all(), [
            'CustomerFirstName' => ['required', 'string', 'max:255'],
            'CustomerLastName' => ['required', 'string', 'max:255'],
            'CustomerMobileNo' => ['required','numeric','digits:11','unique:Customer'],
            'CustomerEmail' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $ip = $_SERVER['REMOTE_ADDR'];
        $project_id = config('app.project_id');

        if ($validator->passes()) {
            $customer = new User();
            $customer->ProjectID = $project_id;
            $customer->CustomerFirstName = $request->CustomerFirstName;
            $customer->CustomerLastName = $request->CustomerLastName;
            $customer->CustomerMobileNo = $request->CustomerMobileNo;
            $customer->CustomerEmail = $request->CustomerEmail;
            $customer->CreatedDate = Carbon::now()->format('Y-m-d');
            $customer->EditedDate = Carbon::now()->format('Y-m-d');
            $customer->CreatedIP = $ip;
            $customer->CreatedDeviceState = 'dhaka';
            $customer->password = bcrypt($request->password);
            $customer->Status = '1';
            if ($customer->save()){
                ///customer/profile
                if(Auth::attempt(['CustomerMobileNo' => $request->CustomerMobileNo, 'password' => $request->password,'ProjectID' =>config('app.project_id')])) {

                    $created_at = Carbon::now();
                    $coupon_log = new CouponLog();
                    $coupon_log->ProjectID = $project_id;
                    $coupon_log->UserId = $customer->CustomerID;
                    $coupon_log->CouponCode = $request->couponCode;
                    $coupon_log->Mobile = $request->CustomerMobileNo;
                    $coupon_log->CouponName = $request->offer_name;
                    $coupon_log->Offer = $request->offer;
                    $coupon_log->OfferType = "percentage";
                    $coupon_log->CreatedAt = $created_at;
                    $coupon_log->UpdatedAt = $created_at;
                    $coupon_log->ValidUpto = $created_at->addMinutes(30);
                    $coupon_log->save();

                    return response()->json(['success'=>'Registration Successfully Generated','CouponID' =>$coupon_log->CouponID]);

                }  else {
                    $this->incrementLoginAttempts($request);
                    return response()->json(['error'=>'Customer Not Active']);
                }

                $this->incrementLoginAttempts($request);
                return $this->sendFailedLoginResponse($request);
            }
            return response()->json(['error'=>'Something went wrong']);
        }

        return response()->json(['error'=>$validator->errors()->all()]);
    }

    public function registrationSuccess(){
        return view('auth.registration_success');
    }

}
