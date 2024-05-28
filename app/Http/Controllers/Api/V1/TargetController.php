<?php
namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Auth;
use App\Target;
use App\Territory;
use App\User;
use App\Http\Requests\TargetStoreRequest;
use App\Http\Requests\TargetUpdateRequest;

use App\Http\Resources\Target as TargetResource;


class TargetController extends Controller{

    public function index(){
        //return Target::all();
        return TargetResource::collection(Target::all());
    }

    public function store(TargetStoreRequest $request){
        $target= new Target;
        $target->date=date("Y-m-d",strtotime($request->date));
        $target->territory_id=$request->territory_id;
        $target->technitian_id=$request->technitian_id;
        $target->warranty_service=$request->warranty_service;
        $target->post_warranty_service=$request->post_warranty_service;
        $target->installation=$request->installation;
        $target->preodic_service=$request->preodic_service;
        $target->post_warranty_visit=$request->post_warranty_visit;
        $target->total = $request->total;
        $target->note = $request->note;
        $target->engineer_id=$request->engineer_id;
        $target->creator_id=Auth::user()->id;
        $target->updater_id=Auth::user()->id;
        $target->save();

        return new TargetResource($target);
    }

    public function update(TargetUpdateRequest $request, $id) {

        $target=Target::findOrFail($id);
        $target->date=date("Y-m-d",strtotime($request->date));
        $target->territory_id=$request->territory_id;
        $target->technitian_id=$request->technitian_id;
        $target->warranty_service=$request->warranty_service;
        $target->post_warranty_service=$request->post_warranty_service;
        $target->installation=$request->installation;
        $target->preodic_service=$request->preodic_service;
        $target->post_warranty_visit=$request->post_warranty_visit;
        $target->total=$request->total;
        $target->note=$request->note;
        $target->engineer_id=$request->engineer_id;
        $target->creator_id=Auth::user()->id;
        $target->updater_id=Auth::user()->id;
        $target->save();

        return  new TargetResource($target);
    }

    public function show($id){
        return new TargetResource(Target::find($id));
    }

    public function destroy($id){
        $target = Target::findOrFail($id);
        $target ->delete();
        return '';
    }

}
