<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\OperatorInfoResource;
use App\OperatorInformation;
use App\UserToken;
use Illuminate\Http\Request;
use Image;

class OperatorInformationController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $area_id = $request->area_id;
        $operators = OperatorInformation::query();
        if ($request->has('search')){
            $operators = $operators->where(function ($query) use ($search){
                $query->where('operator_name', 'like', '%' . $search . '%')->orWhere('mobile', 'like', '%' . $search . '%');
            });
        }
        if (!empty($request->area_id)){
            $operators = $operators->where('area', $area_id);
        }
        $operators = $operators->with('area_name','district_name')->paginate(30);

        return OperatorInfoResource::collection($operators);
    }

    public function store(Request $request)
    {
        $exists = OperatorInformation::where('mobile', $request->mobile)
            ->orWhere('nid_no', $request->nid_no)->get();
        if (count($exists) > 0) {
            return response()->json([
                'status' => 0,
                'message' => 'Mobile number or NID already exist!',
            ]);
        }

        $operator = new OperatorInformation();
        $operator->operator_name          = $request->operator_name;
        $operator->village                = $request->village;
        $operator->post_office            = $request->post_office;
        $operator->police_station         = $request->police_station;
        $operator->area                   = $request->area;
        $operator->district               = $request->district;
        $operator->mobile                 = $request->mobile;
        $operator->training_level         = $request->training_level;
        $operator->training_date          = date('Y-m-d', strtotime($request->training_date));
        $operator->training_venue         = $request->training_venue;
        $operator->total_training_days    = $request->total_training_days;
        $operator->operating_experience   = $request->operating_experience;
        $operator->education              = $request->education;
        $operator->nid_no                 = $request->nid_no;
        $operator->owner_name =                      $request->owner_name;
        $operator->harvester_model =                      $request->harvester_model;
        $operator->chassis_number =                      $request->chassis_number;

        if ($request->hasFile('image_url')) {
            $image       = $request->file('image_url');
            $filename    = time() . "." . $image->getClientOriginalExtension();
            $image_resize = Image::make($image->getRealPath());
            $image_resize->save(public_path('/operator_images/' . $filename));

            $operator->image_url = $filename;
        }
        $operator->save();

        return response()->json([
            'status' => 1,
            'message' => 'Saved Successfully.'
        ]);
    }
}
