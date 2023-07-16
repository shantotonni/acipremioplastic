<?php

namespace App\Http\Controllers;

use App\Model\AddressType;
use App\Model\CustomerAddress;
use App\Model\Invoice;
use App\User;
use Barryvdh\DomPDF\Facade as PDF;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CustomerDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function customerProfile()
    {
        $customer = Auth::user();
        return view('customer.profile',compact('customer'));
    }

    public function customerProfileUpdate(Request $request){

        $validator = Validator::make($request->all(), [
            'CustomerFirstName' => ['required', 'string', 'max:255'],
            'CustomerLastName' => ['required', 'string', 'max:255'],
            'CustomerMobileNo' => 'required|numeric|digits:11',
            'CustomerEmail' => ['required', 'string', 'email', 'max:255'],
        ]);

        $ip = $_SERVER['REMOTE_ADDR'];

        if ($validator->passes()) {
            $customer = User::find($request->CustomerID);
            $customer->CustomerFirstName = $request->CustomerFirstName;
            $customer->CustomerLastName = $request->CustomerLastName;
            $customer->CustomerEmail = $request->CustomerEmail;
            $customer->CustomerMobileNo = $request->CustomerMobileNo;
            $customer->CreatedDate = Carbon::now()->format('Y-m-d');
            $customer->CreatedIP = $ip;
            $customer->CreatedDeviceState = 'dhaka';
            if ($customer->save()){
                return response()->json(['success'=>'User info Successfully Updated']);
            }
            return response()->json(['error'=>'Something went wrong']);
        }

        return response()->json(['error'=>$validator->errors()->all()]);
    }

    public function customerAddress()
    {
        $user = User::where('CustomerID',Auth::user()->CustomerID)->with('customerAddress')->first();
        return view('customer.address',compact('user'));
    }

    public function customerAddressCreate(){
        $address_types = AddressType::all();
        $districts = DB::select(DB::raw('SELECT * FROM vDistrict'));
        $thanas = DB::select(DB::raw('SELECT * FROM vUpazilla'));
        return view('customer.address_create',compact('address_types','districts','thanas'));
    }

    public function customerAddressStore(Request $request){

        $district = explode('-',$request->District);
        $thana = explode('-',$request->Thana);

        $ip = $_SERVER['REMOTE_ADDR'];
       // dd(Auth::user()->CustomerID);

        $customer_address = new CustomerAddress();
        $customer_address->CustomerID = Auth::user()->CustomerID;
        $customer_address->DistrictCode = $district[0];
        $customer_address->DistrictName = $district[1];
        $customer_address->ThanaCode = $thana[0];
        $customer_address->ThanaName = $thana[1];
        $customer_address->CustomerAddress = $request->CustomerAddress;
        $customer_address->CreatedDate = Carbon::now();
        $customer_address->CreatedIP = $ip;
        $customer_address->CreatedDeviceState = 'dhaka';
        $customer_address->EditedDate = Carbon::now();
        $customer_address->EditedIP = $ip;
        $customer_address->EditedDeviceState = 'dhaka';
        $customer_address->AddressTypeID = $request->AddressTypeID;
        $customer_address->save();

        Toastr::success('Customer Address Created successfully :)' ,'Success');
        return redirect()->route('customer.address');

    }

    public function customerAddressEdit($id){
        $customer_address =CustomerAddress::where('AddressID',$id)->first();
        $address_types = AddressType::all();
        $districts = DB::select(DB::raw('SELECT * FROM vDistrict'));
        $thanas = DB::select(DB::raw('SELECT * FROM vUpazilla'));
        return view('customer.address_edit',compact('address_types','districts','thanas','customer_address'));

    }

    public function customerAddressUpdate(Request $request,$id){

        $customer_address = CustomerAddress::where('AddressID',$id)->first();

        $district = explode('-',$request->District);
        $thana = explode('-',$request->Thana);
        $ip = $_SERVER['REMOTE_ADDR'];

        $customer_address->CustomerID = Auth::user()->CustomerID;
        $customer_address->DistrictCode = $district[0];
        $customer_address->DistrictName = $district[1];
        $customer_address->ThanaCode = $thana[0];
        $customer_address->ThanaName = $thana[1];
        $customer_address->CustomerAddress = $request->CustomerAddress;
        $customer_address->CreatedDate = Carbon::now();

        $customer_address->CreatedIP = $ip;
        $customer_address->CreatedDeviceState = 'dhaka';
        $customer_address->EditedDate = Carbon::now();
        $customer_address->EditedIP = $ip;
        $customer_address->EditedDeviceState = 'dhaka';
        $customer_address->AddressTypeID = $request->AddressTypeID;
        $customer_address->update();

        Toastr::success('Customer Address Updated successfully :)' ,'Success');
        return redirect()->route('customer.address');
    }

    public function customerAddressDelete($id){
        $customer_address = CustomerAddress::where('AddressID',$id)->first();
        $customer_address->delete();
        Toastr::success('Customer Address Deleted successfully :)' ,'Success');
        return redirect()->route('customer.address');
    }

    public function customerStore(Request $request){
        dd($request->all());
    }

    public function customerOrder()
    {
        $orders = Invoice::where('CustomerID',Auth::user()->CustomerID)->with('invoiceStatus')->orderBy('InvoiceNo','desc')->get();
        return view('customer.order',compact('orders'));
    }

    public function customerChangePassword()
    {
        return view('customer.change_password');
    }

    public function customerChangePasswordStore(Request $request){

        $validator = Validator::make($request->all(), [
            'previous_password' => 'required|min:6',
            'password' => 'required|min:6|confirmed',
        ]);

        $current_password = Auth::User()->password;

        if ($validator->passes()) {
            if(Hash::check($request->previous_password, $current_password))
            {
                if(Hash::check($request->password, $current_password)){
                    return response()->json(['success'=>'Previous Password and Old Password Same']);
                }else{
                    $customer = User::find(Auth::User()->CustomerID);
                    $customer->password = bcrypt($request->password);
                    $customer->save();
                    return response()->json(['success'=>'Password Change successfully :)']);
                }

            }else{
                return response()->json(['success'=>'Previous Password Not Correct :)']);
            }
        }

        return response()->json(['error'=>$validator->errors()->all()]);


    }

    public function customerInvoicePrint($id){

        $invoice = Invoice::where('InvoiceNo',$id)->with('invoiceDetail','coupon')->first();

        $pdf = PDF::loadView('customer.invoice_print', compact('invoice'), [], 'utf-8');
        return $pdf->stream('Invoice.pdf');

    }

}

