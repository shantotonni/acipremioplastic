<?php

namespace App\Http\Controllers;

use App\Exports\SalesInquiryExport;
use App\Exports\SsrExpenseExport;
use App\SaleInquiry;
use App\SSRExpense;
use App\SSRSalary;
use App\Territory;
use App\User;
use App\UserArea;
use App\UserTerritory;
use App\UserToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Intervention\Image\Facades\Image;

class SalesInquiryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function list()
    {
        return view('sales_inquiry.list');
    }

    public function getSalesInquiry(Request $request)
    {
        if (request()->ajax()) {
            $sales_inquiry = SaleInquiry::query()->with('upazilla', 'inquiryType', 'product', 'useType', 'implement', 'visitResult', 'occupation', 'inquiry_last_status.visit_result','customer_inquiry','customer_inquiry.user');

            if (!empty($request->from_date && !empty($request->to_date))) {
                $from = $request->from_date;
                $to = $request->to_date;
                $sales_inquiry = $sales_inquiry->whereBetween('created_at', [$from, $to]);
            } else {
                $sales_inquiry = $sales_inquiry;
            }

            return Datatables::of($sales_inquiry)
                ->addColumn('upazilla_name', function ($data) {
                    return isset($data->upazilla) ? $data->upazilla->name : '';
                })
                ->addColumn('InquiryTypeName', function ($data) {
                    return isset($data->inquiryType) ? $data->inquiryType->InquiryTypeName : '';
                })
                ->addColumn('ProductName', function ($data) {
                    return isset($data->product) ? $data->product->ProductName : '';
                })
                ->addColumn('UseTypeName', function ($data) {
                    return isset($data->useType) ? $data->useType->UseTypeName : '';
                })
                ->addColumn('ImplementName', function ($data) {
                    return isset($data->implement) ? $data->implement->ImplementName : '';
                })
                ->addColumn('VisitResultName', function ($data) {
                    return isset($data->visitResult) ? $data->visitResult->VisitResultName : '';
                })
                ->addColumn('OccupationName', function ($data) {
                    return isset($data->occupation) ? $data->occupation->OccupationName : '';
                })
                ->addColumn('SSRName', function ($data) {
                    return isset($data->ssr) ? $data->ssr->name : '';
                })
                ->addColumn('SSRMobile', function ($data) {
                    return isset($data->ssr) ? $data->ssr->mobile : '';
                })
                ->addColumn('CreatedAt', function ($data) {
                    return isset($data->created_at) ? $data->created_at : '';
                })
                ->addColumn('Status', function ($data) {
                    return isset($data->inquiry_last_status->visit_result) ? $data->inquiry_last_status->visit_result->VisitResultName : 'Unsold';
                })
                ->addColumn('entryby', function ($data) {
                    return isset($data->customer_inquiry) ? $data->customer_inquiry->EntryBy:'';
                })
                ->addColumn('UserName', function ($data) {
                    return isset($data->customer_inquiry->user) ? $data->customer_inquiry->user->Name:'';
                })
                ->make(true);
        }
    }

    public function salesInquiryExport(Request $request)
    {
        return Excel::download(new SalesInquiryExport($request), 'Sales_inquiry.xlsx');

//        $sales_inquiry = SaleInquiry::orderBy('id','asc')
//            ->with(['upazilla','inquiryType','product','useType','implement','visitResult','occupation','ssr','customer_inquiry','customer_inquiry.user'])
//            ->get();
//        $result = [];
//
//        foreach ($sales_inquiry as $inquiry){
//            $result[] = [
//                'id' =>$inquiry->id,
//                'village_name' =>$inquiry->village_name,
//                'inquiry_name' =>$inquiry->inquiry_name,
//                'inquiry_mobile'=>$inquiry->inquiry_mobile,
//                'MotorsMSRCode' =>$inquiry->MotorsMSRCode,
//                'upazilla_name' =>isset($inquiry->upazilla) ? $inquiry->upazilla->name : '',
//                'InquiryTypeName'=>isset($inquiry->inquiryType) ? $inquiry->inquiryType->InquiryTypeName : '',
//                'SSR_name' =>isset($inquiry->ssr) ? $inquiry->ssr->name : '',
//                'SSR_mobile' =>isset($inquiry->ssr) ? $inquiry->ssr->mobile : '',
//                'ProductCode' =>$inquiry->ProductCode,
//                'product_name' =>isset($inquiry->product) ? $inquiry->product->ProductName : '',
//                'useTypeName' =>isset($inquiry->useType) ? $inquiry->useType->UseTypeName:'',
//                'implementName' =>isset($inquiry->implement) ? $inquiry->implement->ImplementName : '',
//                'visitResultName' =>isset($inquiry->visitResult) ? $inquiry->visitResult->VisitResultName : '',
//                'occupationName' =>isset($inquiry->occupation) ? $inquiry->occupation->OccupationName : '',
//                'customer_brand_model' =>$inquiry->customer_brand_model,
//                'inquirytype' =>$inquiry->inquirytype,
//                'reference_no' =>$inquiry->reference_no,
//                'created_at' =>$inquiry->created_at,
//                'EntryBy' =>isset($inquiry->customer_inquiry) ? $inquiry->customer_inquiry->EntryBy:'',
//                'Name' =>isset($inquiry->customer_inquiry->user) ? $inquiry->customer_inquiry->user->Name : '',
//                'Status' =>isset($inquiry->inquiry_last_status->visit_result) ? $inquiry->inquiry_last_status->visit_result->VisitResultName : 'Unsold',
//            ];
//        }
//
//        $this->exportexcel($result, 'Sales Inquiry');

    }

    public function ssrExpense(Request $request)
    {
        $ssr_expense = SSRExpense::latest()->with('user');
        if ($request->has('period')){
            $ssr_expense = $ssr_expense->where('period',$request->period);
        }

        $ssr_expense = $ssr_expense->paginate(10);
        return view('sales_inquiry.ssr_expense', compact('ssr_expense'));
    }

    public function createSsrExpense()
    {
        $ssrNames = User::where('is_ssr','Y')->get();
        return view('sales_inquiry.ssr_expense_create', compact('ssrNames'));
    }

    public function storeSsrExpense(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required',
            'opening_km' => 'required',
            'bike_no' => 'required',
            'period' => 'required',
            'opening_image' => 'required'
        ]);

        $ssrExpense = new SSRExpense;
        $ssrExpense->user_id = $request->user_id;
        $ssrExpense->opening_km = $request->opening_km;
        $ssrExpense->closing_km = $request->closing_km;
        $ssrExpense->bike_no = $request->bike_no;
        $ssrExpense->period = $request->period;

        if ($request->hasFile('opening_image')) {
            $image       = $request->file('opening_image');
            $filename    = time().rand(100,1000).".". $image->getClientOriginalExtension();
            $image_resize = Image::make($image->getRealPath());
            $image_resize->save(public_path('/ssr_expense/' .$filename));
            $ssrExpense->opening_image = $filename;
        }
        $ssrExpense->save();

        return redirect(route('ssr.expense'))->with('success', 'SSR expense created successfully.');
    }

    public function editSsrExpense($id)
    {
        // dd($id);
        $ssrNames = User::where('is_ssr','Y')->get();
        $expense = SSRExpense::find($id);
        return view('sales_inquiry.ssr_expense_edit', compact('expense','ssrNames'));
    }

    public function updateSsrExpense(Request $request)
    {
        // dd($request->all());
        $ssrExpense = SSRExpense::find($request->id);
        $ssrExpense->user_id = $request->user_id;
        $ssrExpense->opening_km = $request->opening_km;
        $ssrExpense->closing_km = $request->closing_km;
        $ssrExpense->bike_no = $request->bike_no;
        $ssrExpense->period = $request->period;

        if ($request->hasFile('opening_image')) {
            $image       = $request->file('opening_image');
            $filename    = time().rand(100,1000).".". $image->getClientOriginalExtension();
            $image_resize = Image::make($image->getRealPath());
            $image_resize->save(public_path('/ssr_expense/' .$filename));
            $ssrExpense->opening_image = $filename;
        }
        $ssrExpense->save();

        return redirect(route('ssr.expense'))->with('success', 'SSR expense updated successfully.');
    }

    public function exportSsrExpense(Request $request)
    {
        return Excel::download(new SsrExpenseExport($request) ,'ssr_expense.xlsx');
    }

    public function ssrExpenseShow($id)
    {
        $ssr_expense = SSRExpense::where('id', $id)->first();
        //dd($ssr_expense);
    }

    public function ssrSalaryList(Request $request)
    {

        $date = \Carbon\Carbon::now();
        $lastMonth =  $date->subMonth()->format('Ym');
        $Period = $lastMonth;
        $salaries = SSRSalary::query();
        if ($request->has('Period')) {
            $Period = $request->Period;
            $salaries = $salaries->where('Period', $Period);
        } else {
            $salaries = $salaries->where('Period', $Period);
        }

        if (Auth::user()->role_id == 4) {
            $salaries = $salaries->where('Status', 'checked');
        }
        if (Auth::user()->role_id == 5) {
            $salaries = $salaries->where('Status', 'verified');
        }

        $salaries = $salaries->orderBy('Period', 'desc')->paginate(10);
        return view('sales_inquiry.ssr_salary', compact('salaries', 'Period'));
    }

    public function ssrSalaryListPrintPdf(Request $request)
    {
        $salaries = SSRSalary::orderBy('Period', 'desc')->get();
        return view('sales_inquiry.ssr_salary_print_pdf_view', compact('salaries'));
    }

    public function ssrSalaryExport(Request $request)
    {
        $salaries = SSRSalary::orderBy('Period', 'desc')->get()->toArray();
        $this->exportexcel($salaries, 'SSR Salary');
        return redirect()->back();
    }

    public function ssrSalaryDetails($Period, $userid, $staffid)
    {
        $salary = SSRSalary::where('Period', $Period)->where('userid', $userid)->where('staffid', $staffid)->first();

        $technitian_id = $userid;
        $technician = User::where('id', $technitian_id)->first();

        $user_territory = UserTerritory::where('user_id', $technitian_id)->first();
        if (!$user_territory) {
            return response()->json(['error' => "No Territory Defined for this user"], 422);
        }
        $territory = Territory::find($user_territory->territory_id);

        $area_id = $territory->area_id;
        $user_area = UserArea::where('area_id', $area_id)->first();
        if (!$user_area) {
            return response()->json(['error' => "Engineer undefined"], 422);
        }
        $engineer = User::where('id', $user_area->user_id)->first();

        return view('sales_inquiry.ssr_salary_details', compact('salary', 'engineer', 'territory', 'technician'));
    }

    public function ssrSalaryPrintAllPdf(Request $request)
    {
        $salaries = SSRSalary::where('Period', $request->Period)->where('Status', 'approved')->get();
        return view('sales_inquiry.ssr_salary_print_all_in_pdf', compact('salaries'));
    }

    public function ssrSalaryApprovedDisapproved($Period, $userid, $staffid)
    {
        DB::table('SSRSalary')->where('Period', $Period)->where('userid', $userid)->where('staffid', $staffid)->update([
            'Status' => 'checked'
        ]);
        return redirect()->back();
    }

    public function ssrSalaryAllVerified($Period)
    {

        DB::table('SSRSalary')->where('Period', $Period)->where('Status', 'checked')->update([
            'Status' => 'verified'
        ]);
        return redirect()->back();
    }

    public function ssrSalaryAllApproved($Period)
    {
        DB::table('SSRSalary')->where('Period', $Period)->where('Status', 'verified')->update([
            'Status' => 'approved'
        ]);
        return redirect()->back();
    }

    public function ssrSalaryModule(Request $request){
        $from_date = '';
        $to_date = '';
        if ($request->has('from_date') && $request->has('to_date')) {
            $from_date = date('Y-m-d',strtotime($request->from_date));
            $to_date = date('Y-m-d',strtotime($request->to_date));
            $salaries  = DB::select("exec usp_doLoadSSRSalary '$from_date','$to_date'");
            $salaries = collect($salaries);
        }else{
            $salaries = [];
        }

        return view('sales_inquiry.ssr_salary_module', compact('salaries', 'from_date','to_date'));
    }

    function exportexcel($result, $filename)
    {
        $arrayheading[0] = !empty($result) ? array_keys($result[0]) : [];
        $result = array_merge($arrayheading, $result);

        header("Content-Disposition: attachment; filename=\"{$filename}.xls\"");
        header("Content-Type: application/vnd.ms-excel;");
        header("Pragma: no-cache");
        header("Expires: 0");
        $out = fopen("php://output", 'w');
        foreach ($result as $data) {
            fputcsv($out, $data, "\t");
        }
        fclose($out);
        exit();
    }
}
