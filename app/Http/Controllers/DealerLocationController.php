<?php

namespace App\Http\Controllers;

use App\Model\Dealer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DealerLocationController extends Controller
{
    //
    public function index(){
        $districts = DB::select(DB::raw('SELECT * FROM vDistrict'));

        return view('pages.dealer-location', compact('districts'));
    }

    public function getDealer(Request $request)
    {
        $district = $request->district;
        $productGroup = $request->productGroup;
        $upazila = null;

        if (!empty($request->upazila)) {
            $parts = explode('-', $request->upazila);
            $upazila = $parts[0]; // This will give you "1233"
        }

        $query = Dealer::query();

        if($district){
            $query->where('DistrictCode', $district);
        }

        if ($upazila) {
            $query->where('UpazillaCode', $upazila);
        }

        if ($productGroup) {
            $query->where('ProductGroup', $productGroup);
        }

        $dealers = $query->orderBy('DealerId', 'DESC')->get();
        $location = [];

        foreach ($dealers as $dealer) {
            $location[] = [
                'lan' => $dealer->Longitude,
                'lat' => $dealer->Latitude,
            ];
        }
        $response = [
            'dealers' => $dealers, // Return the list of dealers
            'locations' => $location // Return the list of locations
        ];
        return response()->json($response);
    }
}
