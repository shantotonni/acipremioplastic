<?php

namespace App\Http\Controllers\Api\V1;

use App\Area;
use App\CallType;
use App\CustomerToken;
use App\District;
use App\Http\Controllers\Controller;
use App\Http\Resources\Tips as TipsResource;
use App\Http\Resources\SalesProductCategory as SalesProductCategoryResource;
use App\Http\Resources\SalesProduct as SalesProductResource;
use App\Order;
use App\Product;
use App\Section;
use App\ServiceIncomeCategory;
use App\ServiceTips;
use App\ServiceType;
use App\Territory;
use App\TractorParts;
use App\Upazila;
use App\HappyCustomer;
use App\Http\Resources\Product as ProductResource;
use App\Http\Resources\HappyCustomer as HappyCustomerResource;
use App\Http\Resources\ProductModelResource;
use App\Http\Resources\PushNotificationResource;
use App\ProductModel;
use App\PushNotification;
use App\SalesManagerInfo;
use App\SalesProduct;
use App\SalesProductCategory;
use App\ServiceManager;
use App\ShowroomCentre;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GetAllDataController extends Controller
{
    public function getPartsBySection(Request $request){
        $section_id = $request->section_id;
        $parts = TractorParts::query();
        if($request->section_id){
            $parts = $parts->where('section_id',$section_id);
        }elseif($request->has('search')){
            $query = $request->search;
            $parts = $parts->where('custom_name', 'LIKE', '%'.$query.'%');
        }
        else{
            $parts = $parts;
        }

        $parts = $parts->paginate(10);
    

        return \App\Http\Resources\TractorParts::collection($parts);
    }
    
    public function getPartsByModel(Request $request){
        $product_model_id = $request->product_model_id;
        $parts = TractorParts::query()->with('product');
        
        if($request->product_model_id && $request->has('search')){
            $query = $request->search;
            $parts = $parts->where('product_model_id',$product_model_id)->where('custom_name', 'LIKE', '%'.$query.'%');
        }
        elseif($request->product_model_id){
            $parts = $parts->where('product_model_id',$product_model_id);
        }elseif($request->has('search')){
            $query = $request->search;
            $parts = $parts->where('custom_name', 'LIKE', '%'.$query.'%');
        }
        else{
            $parts = $parts;
        }

        $parts = $parts->paginate(70);
        //$parts = [];

        return \App\Http\Resources\TractorParts::collection($parts);
    }

    public function getArea(){
        $areas = Area::where('id','!=',1)->get();
        return response()->json([
            'status'=>200,
            'areas'=>$areas
        ]);
    }

    public function getSection(){
        $section = Section::with('product')->get();
        return response()->json([
            'status'=>200,
            'section'=>$section
        ]);
    }

    public function getProduct(){
        return ProductResource::collection(Product::orderBy('id','desc')->where('type','tractor')->get());
    }
    
    public function getProductModel(){
        $product_model = ProductModel::where('product_id', 1)->get();
        return ProductModelResource::collection($product_model);
    }
    public function getCallType(){
        $call_type = CallType::all();
        return response()->json([
            'status'=>200,
            'call_type'=>$call_type
        ]);
    }
    public function getServiceType(){
        $service_type = ServiceType::all();
        return response()->json([
            'status'=>200,
            'service_type'=>$service_type
        ]);
    }
    public function getTerritory(){
        $territory = Territory::all();
        return response()->json([
            'status'=>200,
            'territory'=>$territory
        ]);
    }
    public function getServiceTips(Request $request){
        $token = str_replace("token ","",$request->header('Authorization'));
        $customer_token = CustomerToken::where("token",$token)->first();
        if(!$customer_token){ return response()->json(['error'=>"Unauthorized"],401);}
        
        $tips = ServiceTips::all();
        return TipsResource::collection($tips);
    }

    public function getDistrict(){
        $districts = District::select('id','name','name_bn','code')->get();
        return response()->json([
            'status'=>1,
            'districts'=>$districts
        ]);
    }

    public function getUpazila(){
        $upazilas = Upazila::select('id','district_id','name','name_bn','code')->get();
        return response()->json([
            'status'=>1,
            'upazilas'=>$upazilas
        ]);
    }
    
    public function getSalesProductCategory(Request $request){
        $token = str_replace("token ","",$request->header('Authorization'));
        $customer_token = CustomerToken::where("token",$token)->first();
        if(!$customer_token){ return response()->json(['error'=>"Unauthorized"],401);}

        $categories = SalesProductCategory::all();
        return response()->json([
            'status'=>1,
            'categories'=>SalesProductCategoryResource::collection($categories)
        ]);
    }
    
    public function getSalesProductByCategory(Request $request){
        $token = str_replace("token ","",$request->header('Authorization'));
        $customer_token = CustomerToken::where("token",$token)->first();
        if(!$customer_token){ return response()->json(['error'=>"Unauthorized"],401);}

        $products = SalesProduct::where('sales_product_category_id', $request->category_id)->get();

        return response()->json([
            'status'=>1,
            'products'=>SalesProductResource::collection($products)
        ]);
    }
    public function getAllPushNotification(Request $request){
        $token = str_replace("token ","",$request->header('Authorization'));
        $customer_token = CustomerToken::where("token",$token)->first();
        if(!$customer_token){ return response()->json(['error'=>"Unauthorized"],401);}

        $notifications = PushNotification::select('id','title','message','image')->orderBy('id', 'desc')->get();
        return PushNotificationResource::collection($notifications);
    }
    public function getShowroomCentre(Request $request){
        $token = str_replace("token ","",$request->header('Authorization'));
        $customer_token = CustomerToken::where("token",$token)->first();
        if(!$customer_token){ return response()->json(['error'=>"Unauthorized"],401);}

        $showrooms = ShowroomCentre::leftJoin('areas','areas.id','=','showroom_centre.area_id')->select('showroom_centre.id','showroom_centre.name','areas.name as area_name','showroom_centre.address','showroom_centre.mobile_number as mobile','showroom_centre.lat','showroom_centre.lon')->get();
        return response()->json([
            // 'status'=>1,
            'data'=>$showrooms
        ]);
    }
    
    public function getSalesManagerInfo(Request $request){
        $token = str_replace("token ","",$request->header('Authorization'));
        $customer_token = CustomerToken::where("token",$token)->first();
        if(!$customer_token){ return response()->json(['error'=>"Unauthorized"],401);}
      
        $salesManagers = SalesManagerInfo::leftJoin('areas','areas.id','=','sales_manager_info.area_id')->where('areas.id', $request->area_id)->select('sales_manager_info.id','sales_manager_info.name','areas.name as area_name','sales_manager_info.mobile_number')->get();
        return response()->json([
            'status'=>1,
            'data'=>$salesManagers
        ]);
    }
    
    public function getServiceManager(Request $request){
        $token = str_replace("token ","",$request->header('Authorization'));
        $customer_token = CustomerToken::where("token",$token)->first();
        if(!$customer_token){ return response()->json(['error'=>"Unauthorized"],401);}
      
        $serviceManagers = ServiceManager::leftJoin('areas','areas.id','=','service_manager.area_id')->where('areas.id', $request->area_id)->select('service_manager.id','service_manager.name','areas.name as area_name','service_manager.mobile_number')->get();
        return response()->json([
            'status'=>1,
            'data'=>$serviceManagers
        ]);
    }

    public function getAllTechnician(){
        $technicians = User::where('role_id',3)->select('id','name','username','role_id')->get();
        return response()->json([
            'status'=>1,
            'technicians'=>$technicians
        ]);
    }

    public function getTechnicianByArea(Request $request){
        $area_id = $request->area_id;
        $technician =  User::select('users.id','users.name','users.mobile','areas.name as area_name','areas.id as area_id')
            ->join('user_territories','user_territories.user_id','users.id')
            ->join('territories','territories.id','user_territories.territory_id')
            ->join('areas','areas.id','territories.area_id')
            ->where('users.role_id',3)
            ->where('areas.id',$area_id)
            ->get();

        return response()->json([
            'status' =>200,
            'technician' => $technician
        ]);
    }

    public function getHappyCustomerFeedback(Request $request){
        $token = str_replace("token ","",$request->header('Authorization'));
        $customer_token = CustomerToken::where("token",$token)->first();
        if(!$customer_token){ return response()->json(['error'=>"Unauthorized"],401);}

        $happy_customer_feedback = HappyCustomer::latest()->get();
        return HappyCustomerResource::collection($happy_customer_feedback);

    }

    public function nearByDealerPoint(Request $request){
        $token = str_replace("token ","",$request->header('Authorization'));
        $customer_token = CustomerToken::where("token",$token)->first();
        if(!$customer_token){ return response()->json(['error'=>"Unauthorized"],401);}

        $lat = $request->lat;
        $lon = $request->lon;
        $distance = 30;

        $result = DB::select("SELECT *
                FROM ( SELECT *, ( 6371 * acos( cos( radians('$lat') ) * cos( radians( lat ) ) *
                cos( radians( lon ) - radians('$lon') ) + sin( radians('$lat') ) *
                sin( radians( lat ) ) ) ) AS distance
                FROM dealer_centers
                 ) myResult
            WHERE myResult.distance < '$distance'
            ORDER BY myResult.distance ASC");

        if (empty($result)){
            return response()->json([
                'status'  =>200,
                'near_by_dealer_point' =>[],
            ]);
        }else{
            return response()->json([
                'status'  =>1,
                'near_by_dealer_point' =>$result,
            ]);
        }
    }

    public function getServiceIncomeCategory(){
        $service_income_category = ServiceIncomeCategory::select('id','name','name_bn','amount','product_id')->get();
        return response()->json([
            'status'=>200,
            'service_income_category' => $service_income_category
        ]);
    }

    public function getAllTractorModel(){
        $tractor_model = ProductModel::where('product_id',1)->select('id','product_id','model_name','model_name_bn','model_code')->get();
        return response()->json([
            'status'=>200,
            'tractor_model' => $tractor_model
        ]);
    }

    public function getAllTerritory(){
        $territory = Territory::select('id','area_id','name')->orderBy('id','desc')->get();
        return response()->json([
            'status'=>200,
            'territory' => $territory
        ]);
    }

}
