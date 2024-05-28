<?php
namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Auth;
use App\Attandence;

use App\User;
use App\Shift;

class AttandenceController extends Controller{


    public function __construct(){
       $this->middleware('auth');
    }
    

    public function index(){
        return Attandence::all();
    }


    public function store(Request $request){

        $this->validate($request, [
           "date"=>"required|date",
           "user_id"=>"required|numeric|exists:users,id",
           "in"=>"nullable|date",
           "out"=>"nullable|date",
           "total_worked_hour"=>"nullable|numeric",
           "worked_hour"=>"nullable|numeric",
           "ot_hour"=>"nullable|numeric",
           "status"=>"required|max:191",
           "remarks"=>"required|max:191",
           "shift_id"=>"required|numeric|exists:shifts,id",

        ]);

        $attandence = new Attandence;
        $attandence->date=date("Y-m-d",strtotime($request->date));
        $attandence->user_id=$request->user_id;
        $attandence->in=date("Y-m-d",strtotime($request->in));
        $attandence->out=date("Y-m-d",strtotime($request->out));
        $attandence->total_worked_hour=$request->total_worked_hour;
        $attandence->worked_hour=$request->worked_hour;
        $attandence->ot_hour=$request->ot_hour;
        $attandence->status=$request->status;
        $attandence->remarks=$request->remarks;
        $attandence->shift_id=$request->shift_id;

        $attandence ->save();
        return $attandence;
    }




    public function update(Request $request, $id) {

        $this->validate($request, [
           "date"=>"required|date",
           "user_id"=>"required|numeric|exists:users,id",
           "in"=>"nullable|date",
           "out"=>"nullable|date",
           "total_worked_hour"=>"nullable|numeric",
           "worked_hour"=>"nullable|numeric",
           "ot_hour"=>"nullable|numeric",
           "status"=>"required|max:191",
           "remarks"=>"required|max:191",
           "shift_id"=>"required|numeric|exists:shifts,id",

        ]); 

        $attandence = Attandence::find($id);
        $attandence->date=date("Y-m-d",strtotime($request->date));
        $attandence->user_id=$request->user_id;
        $attandence->in=date("Y-m-d",strtotime($request->in));
        $attandence->out=date("Y-m-d",strtotime($request->out));
        $attandence->total_worked_hour=$request->total_worked_hour;
        $attandence->worked_hour=$request->worked_hour;
        $attandence->ot_hour=$request->ot_hour;
        $attandence->status=$request->status;
        $attandence->remarks=$request->remarks;
        $attandence->shift_id=$request->shift_id;

        $attandence->save();
        return  $attandence;
    }

    public function show($id){
        return Attandence::find($id); 
    }


    public function destroy($id){
        $attandence = Attandence::findOrFail($id);
        $attandence ->delete();
        return '';
    }

}