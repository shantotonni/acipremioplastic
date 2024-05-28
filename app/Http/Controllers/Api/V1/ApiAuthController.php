<?php
namespace App\Http\Controllers\Api\V1;
use App\Customer;
use App\CustomerToken;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Auth;
use App\User;
use App\Http\Resources\User as UserResource;
use App\UserToken;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\RegisteredCustomer as RegisteredCustomerResource;


class ApiAuthController extends Controller{

     public function login(Request $request){

         if($request->mobile){
             if($customer = Customer::where('mobile',$request->mobile)->first()){
                 if (Hash::check($request->password, $customer->password)){
                     $new_token = $this->createNewToken($customer);
                     return response()->json([
                         'token'=>$new_token->token,
                         'customer' => new RegisteredCustomerResource($customer)
                     ]);
                 }else{
                     return response()->json(['errors' => ['mobile'=>'Invalid moblie or password']], 401);
                 }
             }else{
                 return response()->json(['errors' => ['mobile'=>'Invalid moblie or password']], 401);
             }
         }

        if($user = User::where('username',$request->username)->where('is_active',1)->first()){
            $user->device_token = $request->device_token;
            $user->save();
            if (Hash::check($request->password, $user->password)){
                $new_token = $this->createNewUserToken($user);
                return response()->json(array(
                    'token'=>$new_token->token,
                    'user'=>$user
                ));
            }else{
                return response()->json(['errors' => ['username'=>'Invalid mobile or password']], 401);
            }
        }else{
            return response()->json(['errors' => ['username'=>'Invalid moblie or password']], 401);
        }
     }

    private function createNewUserToken($user){
        $t = UserToken::where('user_id',$user->id)->first();
        if($t){
            $t->delete();
        }
        return UserToken::create(['user_id'=>$user->id,'token'=>Str::random(40)]);
    }

    public function customer_info(Request $request){
        $token = str_replace("token ","",$request->header('Authorization'));
        $customer_token = CustomerToken::where('token',$token)->first();
        $customer = Customer::find($customer_token->customer_id);
        return response()->json($customer);
    }

    public function registration(Request $request){
        
        $customer = Customer::where('code',$request->code)->orWhere('mobile',$request->mobile)->orWhere('chassis',$request->chassis)->exists();

        if ($customer) {
            return response()->json([
                'status' =>0,
                'msg'=>'Duplicate Entry Not Allow'
            ]);
        }
        
        $this->validate($request, [
            "name"=>"required|max:191",
            "date_of_purchase"=>"required",
            "mobile"=>"required|max:11|min:11",
            "password"=>"required|confirmed",
            "area_id"=>"required|numeric",
            "model_id"=>"required|numeric",
            // "image"=>"required",
            "chassis"=>"nullable|max:191",
            "chassis_image"=>"required"
        ]);
        
        $fileName ='';
        if ($request->has('image')) {
            $image = $request->file('image');
            $fileName = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/product_image');
            $image->move($destinationPath, $fileName);
        }
        $fileName2 ='';
        if ($request->has('chassis_image')) {
            $image2 = $request->file('chassis_image');
            $fileName2 = time().'.'.$image2->getClientOriginalExtension();
            $destinationPath2 = public_path('/chassisimage');
            $image2->move($destinationPath2, $fileName2);
        }

        $customer= new Customer;
        $customer->name = $request->name;
        $customer->code = $request->code;
        $customer->product_id =1;
        $customer->date_of_purchase=$request->date_of_purchase;
        $customer->address=$request->address;
        $customer->mobile=$request->mobile;
        $customer->password=Hash::make($request->password);
        $customer->chassis=$request->chassis;
        $customer->area_id=$request->area_id;
        $customer->model_id=$request->model_id;
        $customer->image=$fileName;
        $customer->chassis_image=$fileName2;

        try{
            if ($customer->save()) {            
                $new_token = $this->createNewToken($customer);
            }

        }catch(\Exception $ex) {
            return response()->json([
                'status' => 0,
                'msg' => "Exception Happend"
            ],500);

        }   

        return response()->json([
            'status' =>1,
            'token'=>$new_token->token,
            'customer'=>new RegisteredCustomerResource($customer)
        ]);
    }

    private function createNewToken($customer){
        $t = CustomerToken::where('customer_id',$customer->id)->first();
        if($t){
            $t->delete();
        }
        return CustomerToken::create(['customer_id'=>$customer->id,'token'=>Str::random(40)]);
    }

    public function logout(Request $request){

        $user = User::where('id',$request->technician_id)->first();

        if ($user) {
            $user_token = UserToken::where('user_id',$user->id)->first();
            if ($user_token) {
                $user_token->delete();
            }
        }
        $user->device_token = null;
        $user->save();

        return response()->json(['message'=>"successfully logout"],200);
    }

    public function resetPassword(Request $request)
    {
        $this->validate($request, [
            "mobile"=>"required|max:11|min:11"
        ]);

        $customer = Customer::where('mobile',$request->mobile)->exists();

        if (!$customer) {
            return response()->json([
                'status' =>0,
                'msg'=>'Mobile number not registered!'
            ]);
        }

        $customer = Customer::where('mobile',$request->mobile)->first();
        $newPassword = Str::random(8);
        $customer->password=Hash::make($newPassword);
        $customer->save();

        $smscontent = 'আপনার অস্থায়ী পাসওয়ার্ডঃ '."$newPassword";
        $mobileno = $customer->mobile;

        $respons = $this->sendsms($ip = '192.168.100.213', $userid = 'motors', $password = 'Asdf1234', $smstext = urlencode($smscontent), $receipient = urlencode($mobileno));


        return response()->json([
            'status' =>1,
            'msg'=>'আপনার পাসওয়ার্ড সফলভাবে পাঠানো হয়েছে'
        ]);
    }

    public function updatePassword(Request $request)
    {
        $token = str_replace("token ","",$request->header('Authorization'));
        $customer_token = CustomerToken::where('token',$token)->first();
        $customer = Customer::find($customer_token->customer_id);
        
        if (Hash::check($request->password, $customer->password)){
            $customer->password = Hash::make($request->new_password);
            $customer->save();

            return response()->json([
                'status' =>1,
                'msg' =>'আপনার পাসওয়ার্ড সফলভাবে আপডেট হয়েছে'
            ]);
        }else{
            return response()->json([
                'status' =>0,
                'msg'=>'Invalid Old Password!'
            ]);
        }
    }

    public function sendsms($ip, $userid, $password, $smstext, $receipient) {
        $smsUrl = "http://{$ip}/httpapi/sendsms?userId={$userid}&password={$password}&smsText=" . $smstext . "&commaSeperatedReceiverNumbers=" . $receipient;
        //echo $smsUrl; exit();
        $response = file_get_contents($smsUrl);
        //print_r($response); exit();
        return json_decode($response);
    }

}