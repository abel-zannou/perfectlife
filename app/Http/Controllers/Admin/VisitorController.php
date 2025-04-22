<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Visitor;
use Illuminate\Http\Request;

class VisitorController extends Controller
{
    public function GetAllVisitors()
    {
        $ip_adress = $_SERVER['REMOTE_ADDR'];

        date_default_timezone_set("Africa/Port-Novo");

        $visite_time = date("H:m:s");
        $visite_date = date("d/m/Y");

        $result = Visitor::insert([
            'ip_adress' => $ip_adress,
            'visite_time' => $visite_time,
            'visite_date' => $visite_date,
        ]);

        return $result;
    }
}
