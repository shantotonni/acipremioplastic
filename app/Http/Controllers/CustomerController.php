<?php

namespace App\Http\Controllers;

use App\Area;
use App\Customer;
use App\Exports\CustomerExport;
use App\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

class CustomerController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $from_date = $request->from_date ? date('Y-m-d',strtotime($request->from_date)) : date('Y-m-01', strtotime(date('Y-m-01').' -1 month'));
        $to_date = $request->to_date ? date('Y-m-d',strtotime($request->to_date)) : date('Y-m-d') ;

        $customers = Customer::query()->with('model');
        $mobile = '';
        if ($request->has('mobile')){
            $mobile = $request->mobile;
            $customers = $customers->where('mobile', 'like', '%' . $request->mobile . '%');
        }
        
        if ($request->has('from_date') && $request->has('to_date')){
            $customers = $customers->whereDate('created_at',">=",$from_date)
                            ->whereDate('created_at',"<=",$to_date)
                            ->orderBy('id','desc')->paginate(10);
        }else{
            $customers = $customers->orderBy('id','desc')->paginate(10);
        }
        return view('customer.list',compact('customers','mobile'));
    }

    public function create()
    {
        $areas = Area::all();
        $models = Product::orderBy('id','desc')->where('type','tractor')->get();
        return view("customer.create",compact('areas','models'));
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            "name"=>"required|max:191",
            "code"=>"required|max:191|unique:customers",
            "mobile"=>"required|max:11|min:11|unique:customers",
            "password"=>"required|max:191|confirmed",
            "chassis"=>"nullable|max:191",
            "service_hour"=>"nullable|numeric",
            "area_id"=>"required|numeric|exists:areas,id",
        ]);

        $customer                   = new Customer;
        $customer->name             = $request->name;
        $customer->code             = $request->code;
        $customer->mobile           = $request->mobile;
        $customer->password         = Hash::make($request->password);
        $customer->chassis          = $request->chassis;
        $customer->service_hour     = $request->service_hour;
        $customer->product_id     = $request->product_id;
        $customer->area_id          = $request->area_id;
        $customer->date_of_purchase = Carbon::now();
        $customer->save();

        Session::flash("success", "Customer Created Successfully !");
        return redirect()->route('customers.index');

    }

    public function show($id)
    {
        $customer = Customer::find($id);
        return view('customer.show',compact('customer'));
    }

    public function edit($id)
    {
        $customer = Customer::find($id);
        $areas = Area::all();
        $models = Product::orderBy('id','desc')->where('type','tractor')->get();
        return view('customer.edit',compact('customer','areas','models'));
    }

    public function update(Request $request, $id)
    {

        $this->validate($request,[
            "name"=>"required|max:191",
            "code"=>"required|max:191|unique:customers,code,".$id,
            "mobile"=>"required|max:11|min:11|unique:customers,mobile,".$id,
            "chassis"=>"nullable|max:191",
            "service_hour"=>"nullable|numeric",
            "area_id"=>"required|numeric",
        ]);

        $customer = Customer::find($id);
        $customer->name=$request->name;
        $customer->code=$request->code;
        $customer->mobile=$request->mobile;
        $customer->chassis=$request->chassis;
        $customer->service_hour=$request->service_hour;
        $customer->product_id     = $request->product_id;
        $customer->area_id=$request->area_id;
        $customer->save();

        Session::flash("success", "Customer Updated Successfully !");
        return redirect()->route('customers.index');
    }

    public function destroy($id)
    {
        $customer = Customer::find($id);
        $customer->delete();
        Session::flash("success", "Customer Deleted Successfully !");
        return response()->json(['msg' => 'Data deleted successfully'], 200);
    }

   
    public function exportCustomers(Request $request)
    {
        return Excel::download(new CustomerExport($request) ,'customers_list.xlsx');
    }
}
