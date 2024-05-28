<?php
namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Auth;
use App\Area;
use App\Http\Requests\AreaStoreRequest;
use App\Http\Requests\AreaUpdateRequest;

use App\Http\Resources\Area as AreaResource;


class AreaController extends Controller{


    public function __construct(){
      // $this->middleware('auth');
    }

    public function getAllAreas()
    {
        return AreaResource::collection(Area::all());
    }
    
    public function index(){
        //return Area::all();
        return AreaResource::collection(Area::all());
    }


    public function store(AreaStoreRequest $request){

        $area= new Area;
        $area->name=$request->name;
        $area->code=$request->code;
        $area->save();

        return new AreaResource($area);
    }




    public function update(AreaUpdateRequest $request, $id) {

        $area=Area::findOrFail($id);
        $area->name=$request->name;
        $area->code=$request->code;
        $area->save();

        return  new AreaResource($area);
    }

    public function show($id){
        return new AreaResource(Area::find($id));
    }


    public function destroy($id){
        $area = Area::findOrFail($id);
        $area ->delete();
        return '';
    }

}
