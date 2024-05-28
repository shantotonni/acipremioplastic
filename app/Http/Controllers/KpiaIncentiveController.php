<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use Auth;
use App\KpiaIncentive;
use App\Kpium;
use App\IncentiveFactor;
use App\Http\Requests\KpiaIncentiveStoreRequest;
use App\Http\Requests\KpiaIncentiveUpdateRequest;


class KpiaIncentiveController extends Controller{


    public function __construct(){
       $this->middleware('auth');
    }


    public function index(){
        $kpia_incentives = KpiaIncentive::orderBy('id','Desc')->paginate(20);
        return view("kpia_incentive.kpia_incentive_list",compact("kpia_incentives"));
    }


    public function create(){
        
        $kpia=Kpium::all();
        $incentive_factors=IncentiveFactor::all();
        return view("kpia_incentive.kpia_incentive_create")
             ->with("kpia" ,$kpia)
             ->with("incentive_factors" ,$incentive_factors);;
    }


    public function store(KpiaIncentiveStoreRequest $request){

        $kpia_incentive= new KpiaIncentive;
        $kpia_incentive->kpia_id=$request->kpia_id;
        $kpia_incentive->incentive_factor_id=$request->incentive_factor_id;
        $kpia_incentive->multiplier=$request->multiplier;
        $kpia_incentive->tractor=$request->tractor;
        $kpia_incentive->nmpt=$request->nmpt;
        $kpia_incentive->tractor_and_nmpt=$request->tractor_and_nmpt;
        $kpia_incentive->save();

        Session::flash("success", "Created Succcessfully !");
        return redirect("/kpia_incentive");
    }



    public function edit($id){
        
        $kpia=Kpium::all();
        $incentive_factors=IncentiveFactor::all();
        $kpia_incentive = KpiaIncentive::findOrFail($id);
        return view("kpia_incentive.kpia_incentive_edit",compact("kpia_incentive"))
             ->with("kpia" ,$kpia)
             ->with("incentive_factors" ,$incentive_factors);;

    }

    public function update(KpiaIncentiveUpdateRequest $request, $id) {

        $kpia_incentive=KpiaIncentive::findOrFail($id);
        $kpia_incentive->kpia_id=$request->kpia_id;
        $kpia_incentive->incentive_factor_id=$request->incentive_factor_id;
        $kpia_incentive->multiplier=$request->multiplier;
        $kpia_incentive->tractor=$request->tractor;
        $kpia_incentive->nmpt=$request->nmpt;
        $kpia_incentive->tractor_and_nmpt=$request->tractor_and_nmpt;
        $kpia_incentive->save();

        Session::flash("success", "Edited Succcessfully !");
        return redirect("/kpia_incentive");
    }

    public function show($id){
        $kpia_incentive = KpiaIncentive::find($id);
        return view("kpia_incentive.kpia_incentive_show",compact("kpia_incentive"));
    }


    public function destroy($id){
        $kpia_incentive = KpiaIncentive::findOrFail($id);
        $kpia_incentive ->delete();
        Session::flash("success", "Deleted Succcessfully !");
        return redirect("/kpia_incentive");
    }

}
