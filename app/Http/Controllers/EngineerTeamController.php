<?php

namespace App\Http\Controllers;

use App\Area;
use App\Territory;
use App\User;
use App\UserArea;
use App\UserTerritory;
use Illuminate\Http\Request;

use Auth;

class EngineerTeamController extends Controller{
    
    public function engineer_team(Request $request){
        if(Auth::user()->role_id == 1){
           $areas = Area::all();
        }else{
            $user_area = UserArea::where('user_id',Auth::user()->id)->pluck('area_id');
            $areas = Area::whereIn('id',$user_area)->get();
        }

       $territories = Territory::where('area_id',$request->area_id)->get();

       $technicians = UserTerritory::select('users.username','users.name','territories.name as territory_name','supervisors.name as supervisor_name','supervisors.username as supervisor_staffid')
                                      ->join('users','users.id','user_territories.user_id')
                                      ->leftjoin('users as supervisors','supervisors.id','user_territories.supervisor_id')
                                      ->join('territories','territories.id','user_territories.territory_id')
                                      ->where('territories.area_id',$request->area_id)
                                      ->orderBy('supervisors.id',"ASC")
                                      ->get();

        $engineer = User::join('user_areas','user_areas.user_id','users.id')
                          ->where('user_areas.area_id',$request->area_id)
                          ->first();
        return view('engineer_team',compact(['areas','territories','technicians','engineer']));
                                      

    }
}
