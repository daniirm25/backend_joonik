<?php

namespace App\Http\Controllers;

use Throwable;
use App\Services\LocationServices;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    protected $service;

    public function __construct(LocationServices $service)
    {
        $this->service = $service;
    }

    public function createLocation(Request $request)
    {
        return $this->service->createLocation($request);
    }

    public function locations()
    {
        return $this->service->locations();
    }

    


}
