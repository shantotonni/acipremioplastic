<?php

namespace App\Http\Controllers;

use App\Territory;
use App\Upazila;
use App\User;
use App\UserArea;
use Illuminate\Http\Request;
use App\UserTerritory;

class CommonSearchController extends Controller{

    public function get_upazilla_of_district(Request $request){
        return Upazila::where('district_id',$request->district_id)->get();
    }

    // public function get_union_of_upazilla(Request $request){
    //     return UnionInfo::where('UpazilaCode',$request->UpazillaCode)->get();
    // }

    public function user_of_territory($territory_id){
       $user_territory = UserTerritory::where('territory_id',$territory_id)->pluck('user_id');
       return User::whereIn('id',$user_territory)->where('role_id',3)->get();
    }
    
    public function user_of_area($area_id){
       $user_area = UserArea::where('area_id',$area_id)->pluck('user_id');
       return User::whereIn('id',$user_area)->where('role_id',2)->get();
    }
    
    public function user_of_territory_by_area($area_id){
       $territory = Territory::where('area_id',$area_id)->get();
       return $territory;
    }
}
