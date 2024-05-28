<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use Auth;
use App\Target;
use App\Territory;
use App\User;
use App\Area;
use App\Http\Requests\TargetStoreRequest;
use App\Http\Requests\TargetUpdateRequest;
use App\UserArea;
use DB;

class TargetController extends Controller{


    public function __construct(){
       $this->middleware('auth');
    }

    public function index(Request $request){
        if(Auth::user()->role_id == 1){
            $areas = Area::all();
        }else{
            $user_area = UserArea::where('user_id',Auth::user()->id)->pluck('area_id');
            $areas = Area::whereIn('id',$user_area)->get();
        }
        $targets = Target::select('targets.*','territories.id as territory_id')
                   ->join('territories','territories.id','targets.territory_id')
                   ->where('territories.area_id',$request->area_id)
                   ->whereMonth('date',date('m',strtotime($request->date)))
                   ->whereYear('date',date('Y',strtotime($request->date)))
                   ->orderBy('targets.id','Desc')
                   ->get();
        return view("target.target_list",compact("targets"))->with('areas',$areas);
    }

    public function create(){
        if((Auth::user()->role_id == 1) || (Auth::user()->role_id == 2 && (int)date('d') < 12)){
            // no action
       }else{
           return redirect()->back()->with('danger',"Create Target Restricted");
       }
       
        if(Auth::user()->role_id == 2){
            $user_area = UserArea::where('user_id',Auth::user()->id)->pluck('area_id');
            $areas = Area::whereIn('id',$user_area)->get();
            $area = Area::whereIn('id',$user_area)->first();
            $territories  = $area->territories;
        }else{
            $territories=Territory::all();
            $areas=Area::all();
        }
        
        $users=User::where('role_id',2)->get();
        return view("target.target_create")
             ->with("territories" ,$territories)
             ->with("users" ,$users)
             ->with("areas" ,$areas);
    }

    public function getAllInputData(Request $request)
    {
        $date = $request->date;
        $area_id = $request->area_id;
        $engineer_id = $request->engineer_id;

        $date = $date;
        $area_id = $area_id;
        $engineer_id = $engineer_id;
        
        $territories = DB::select(DB::raw("SELECT t.id as territory_id,t.area_id,t.name as territory_name, ut.user_id as technician_id, 
                        CONCAT(replace(us.username,' ',''),' - ',us.name) as technician from territories t
                        left join user_territories ut on t.id=ut.territory_id
                        left join users us on us.id=ut.user_id
                        where area_id=$area_id and user_id is not null"));

        // dd($territories);
        $string = '';
        $count = 0;
        foreach($territories as $key=>$terr){ $count++;
            $string .= '<input type="hidden" name="territory_id[]" value="'.$terr->territory_id.'">';
            $string .= '<input type="hidden" name="technician_id[]" value="'.$terr->technician_id.'">';
            $string .= '<input type="hidden" name="technician[]" value="'.$terr->technician.'">';
            $string .= '<tr>';
            $string .= '<td>'.$terr->territory_name.'</td>';
            $string .= '<td>'.$terr->technician.'</td>';
            $string .= '<td><input onkeyup="totalWarranty(this,\''.$count.'\')" class="form-control tractor_warranty" type="number" id="tractor_warranty'.$count.'" name="tractor_warranty[]" style="min-width:150px !important;" /></td>';
            $string .= '<td><input onkeyup="totalPostWarranty(this,\''.$count.'\')" class="form-control tractor_post_warranty"  id="tractor_post_warranty'.$count.'" type="number" name="tractor_post_warranty[]"  style="min-width:150px !important;" /></td>';
            $string .= '<td><input onkeyup="totalWarranty(this,\''.$count.'\')" class="form-control nm_warranty"  id="nm_warranty'.$count.'" type="number" name="nm_warranty[]"  style="min-width:150px !important;" /></td>';
            $string .= '<td><input onkeyup="totalPostWarranty(this,\''.$count.'\')" class="form-control nm_post_warranty"  id="nm_post_warranty'.$count.'" type="number" name="nm_post_warranty[]"  style="min-width:150px !important;" /></td>';
            $string .= '<td><input class="form-control total_warranty" type="number" name="total_warranty[]" id="total_warranty'.$count.'" readonly  style="min-width:150px !important;" /></td>';
            $string .= '<td><input class="form-control total_post_warranty" type="number" name="total_post_warranty[]" id="total_post_warranty'.$count.'" readonly  style="min-width:150px !important;" /></td>';
            $string .= '<td><input class="form-control total_service_budget" type="number" name="total_service_budget[]"  id="total_service_budget'.$count.'" readonly  style="min-width:150px !important;" /></td>';
            $string .= '<td><input onkeyup="totalServiceIncomeBudget(this,\''.$count.'\')" class="form-control service_income_budget" type="number" name="service_income_budget[]" id="service_income_budget'.$count.'" /></td>';
            $string .= '</tr>';
        }

            $string .= '<tr>';
            $string .= '<th colspan="2" class="text-center">Total</th>';
            $string .= '<td><input class="form-control" type="number" name="sum_tractor_warranty" id="sum_tractor_warranty" readonly /></td>';
            $string .= '<td><input class="form-control" type="number" name="sum_tractor_post_warranty" id="sum_tractor_post_warranty" readonly /></td>';
            $string .= '<td><input class="form-control" type="number" name="sum_nm_warranty" id="sum_nm_warranty" readonly /></td>';
            $string .= '<td><input class="form-control" type="number" name="sum_nm_post_warranty" id="sum_nm_post_warranty" readonly /></td>';
            $string .= '<td><input class="form-control" type="number" name="sum_total_warranty" id="sum_total_warranty" readonly /></td>';
            $string .= '<td><input class="form-control" type="number" name="sum_total_post_warranty" id="sum_total_post_warranty" readonly /></td>';
            $string .= '<td><input class="form-control" type="number" name="sum_total_service_budget" id="sum_total_service_budget" readonly /></td>';
            $string .= '<td><input class="form-control" type="number" name="sum_service_income_budget" id="sum_service_income_budget" readonly /></td>';
            $string .= '</tr>';

        return response()->json($string);
    }

    public function store(Request $request){
        // dd($request->all());
        $territory_ids = $request->territory_id;

        for($i=0; $i<count($territory_ids); $i++){
            $territory_id = $request->territory_id[$i];
            $technician_id = $request->technician_id[$i];
            $technician = $request->technician[$i];
            $tractor_warranty = $request->tractor_warranty[$i];
            $tractor_post_warranty = $request->tractor_post_warranty[$i];
            $nm_warranty = $request->nm_warranty[$i];
            $nm_post_warranty = $request->nm_post_warranty[$i];
            $total_service_budget = $request->total_service_budget[$i];
            $service_income_budget = $request->service_income_budget[$i];

            if($this->isTargetAlreadyCreatedStore($request->date,$technician_id)){
                return redirect()->back()->with('danger',"Already Target Created for This Month. You can Edit from List View.");
                // return redirect()->back()->with('danger',"Already Target Created for Technitian:[ ".$technician." ] on given month");
            }

            $target= new Target;
            $target->date = date("Y-m-d",strtotime($request->date));
            $target->area_id = $request->area_id;
            $target->engineer_id = $request->engineer_id;
            $target->territory_id=$territory_id;
            $target->technitian_id=$technician_id;
            $target->tractor_warranty=$tractor_warranty;
            $target->tractor_post_warranty=$tractor_post_warranty;
            $target->nm_warranty=$nm_warranty;
            $target->nm_post_warranty=$nm_post_warranty;
            $target->total=$total_service_budget;
            $target->service_income = $service_income_budget;

            $target->warranty_master_total = $tractor_warranty + $nm_warranty;
            $target->post_warranty_master_total = $tractor_post_warranty + $nm_post_warranty;
       
            $target->creator_id=Auth::user()->id;
            $target->updater_id=Auth::user()->id;
            $target->save();
            
        }

        Session::flash("success", "Created Succcessfully !");
        return redirect("/target?area_id=".$request->area_id."&date=$request->date");

        // $target->installation=$request->installation;
        // $target->preodic_service=$request->preodic_service;
        // $target->warranty_service=$request->warranty_service;

        // $target->post_warranty_service=$request->post_warranty_service;
        // $target->post_warranty_visit=$request->post_warranty_visit;

        // $target->warranty_master_total = $request->installation + $request->preodic_service + $request->warranty_service ;
        // $target->post_warranty_master_total=$request->post_warranty_service + $request->post_warranty_visit ;

        // $target->total=$request->installation +
        //                $request->preodic_service +
        //                $request->warranty_service +
        //                $request->post_warranty_service +
        //                $request->post_warranty_visit;

        // $target->service_income = $request->service_income;
        // $target->tractor_spare_parts_lubricants = $request->tractor_spare_parts_lubricants;
        // $target->nm_pt_spare_parts_lubricants = $request->nm_pt_spare_parts_lubricants;


        // $target->note=$request->note;
        // if(Auth::user()->role_id == 1){
        //     $target->engineer_id=$request->engineer_id;
        // }else{
        //     $target->engineer_id = Auth::user()->id;
        // }
        // $target->creator_id=Auth::user()->id;
        // $target->updater_id=Auth::user()->id;
        // $target->save();

        // $territory = Territory::find($request->territory_id);

    }

    private function isTargetAlreadyCreatedStore($date,$technitian_id){
          $target = Target::whereMonth('date',date('m',strtotime($date)))->whereYear('date',date('Y',strtotime($date)))->where('technitian_id',$technitian_id)->first();
          if($target){
              return 1;
          }else{
              return 0;
          }
    }

    private function isTargetAlreadyCreatedUpdate($date,$technitian_id,$id){
        
        $target = Target::whereMonth('date',date('m',strtotime($date)))->whereYear('date',date('Y',strtotime($date)))->where('technitian_id',$technitian_id)->where('id','!=',$id)->first();
        if($target){
            return 1;
        }else{
            return 0;
        }
  }

    public function edit($id){
        if((Auth::user()->role_id == 1) || (Auth::user()->role_id == 2 && (int)date('d') < 12)){
            // no action
       }else{
           return redirect()->back()->with('danger',"Update Restricted");
       }

        if(Auth::user()->role_id == 2){
            $user_area = UserArea::where('user_id',Auth::user()->id)->pluck('area_id');
            $areas = Area::whereIn('id',$user_area)->get();
            $area = Area::whereIn('id',$user_area)->first();
            $territories  = $area->territories;
        }else{
            $territories=Territory::all();
            $areas=Area::all();
        }
    
        $users=User::where('role_id',2)->get();
        $technitians=User::where('role_id',3)->get();
    
        $target = Target::findOrFail($id);
        return view("target.target_edit",compact("target"))
             ->with("territories" ,$territories)
             ->with('technitians',$technitians)
             ->with("users" ,$users)
             ->with("areas" ,$areas);
    }

    public function update(Request $request, $id) {

        if((Auth::user()->role_id == 1) || (Auth::user()->role_id == 2 && (int)date('d') < 12)){
             // no action
        }else{
            return redirect()->back()->with('danger',"Update Restricted");
        }

        $target=Target::findOrFail($id);
        // if($this-> isTargetAlreadyCreatedUpdate($request->date,$request->technitian_id,$id)){           
        //     return redirect()->back()->with('danger',"Already Target Created for this Technitian on given month");
        // }

        $target->date=date("Y-m-d",strtotime($request->date));
        $target->area_id=$request->area_id;
        $target->territory_id=$request->territory_id;
        $target->technitian_id=$request->technitian_id;
        $target->tractor_warranty=$request->tractor_warranty;
        $target->tractor_post_warranty=$request->tractor_post_warranty;
        $target->nm_warranty=$request->nm_warranty;
        $target->nm_post_warranty=$request->nm_post_warranty;
        $target->total=$request->total;
        $target->service_income = $request->service_income;
        $target->note=$request->note;
        
        $target->warranty_master_total = $request->tractor_warranty + $request->nm_warranty;
        $target->post_warranty_master_total = $request->tractor_post_warranty + $request->nm_post_warranty;

        if(Auth::user()->role_id == 1){
            $target->engineer_id=$request->engineer_id;
        }else{
            $target->engineer_id = Auth::user()->id;
        }
        $target->updater_id=Auth::user()->id;
        $target->save();
        Session::flash("success", "Edited Succcessfully !");
        // $area_id = Territory::find($request->territory_id)->area_id;
        return redirect("/target?date=$target->date&area_id=$request->area_id");
    }

    public function show($id){
        $target = Target::find($id);
        return view("target.target_show",compact("target"));
    }

    public function destroy($id){
        $target = Target::findOrFail($id);
        $target ->delete();
        Session::flash("success", "Deleted Succcessfully !");
        return redirect("/target");
    }

}
