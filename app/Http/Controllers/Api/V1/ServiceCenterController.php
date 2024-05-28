<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\GetServiceCenterResource;
use App\ServiceCenter;
use Illuminate\Http\Request;

class ServiceCenterController extends Controller
{
    public function index(){
        $service_center = ServiceCenter::with('area')->get();
        return GetServiceCenterResource::collection($service_center);
    }
}
