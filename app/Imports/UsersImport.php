<?php

namespace App\Imports;

use App\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;

class UsersImport implements ToModel
{

    public function model(array $row)
    {
        //dd($row);
        return new User([
            'name'     => $row[2],
            'username'     => $row[0],
            'company_id'=>1,
            'role_id'=>3,
            'designation'=>'SSR',
            'mobile'=> '"'.$row[3].'"',
            'email'    => $row[0].'@gmail.com',
            'password' => Hash::make(123456),
            'is_active'=> 1,
            'kpi_type_id'=> 3,
            'is_ssr'=> 'Y',
        ]);
    }
}
