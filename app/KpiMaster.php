<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KpiMaster extends Model
{
    protected $table = 'kpi_master';
    
    protected $fillable = [
        'serial',
        'staff_id',
        'name',
        'period',
        'designation',
        'region',
        'territory',
        'warranty_service_target',
        'warranty_service_actual',
        'warranty_service_percentage',
        'post_warranty_service_target',
        'post_warranty_service_actual',
        'post_warranty_service_percentage',
        'csi_percentage',
        'six_hour_percentage',
        'tracking',
        'in_apps',
        'apps_percentage',
        'service_income_target',
        'service_income_actual',
        'service_income_percentage',
        'spare_parts_target',
        'spare_parts_actual',
        'spare_parts_percentage',
        'spare_parts_tractor_target',
        'spare_parts_tractor_actual',
        'spare_parts_tractor_percentage',
        'spare_parts_nm_and_pt_target',
        'spare_parts_nm_and_pt_actual',
        'spare_parts_nm_and_pt_percentage'
    ];
}
