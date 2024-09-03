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
        $upazila = null;

        if (!empty($request->upazila)) {
            $parts = explode('-', $request->upazila);
            $upazila = $parts[0]; // This will give you "1233"
        }

        $query = Dealer::where('DistrictCode', $district);

        if ($upazila) {
            $query->where('UpazillaCode', $upazila);
        }

        $dealers = $query->get();
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
