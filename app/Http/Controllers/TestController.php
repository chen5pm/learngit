<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;


class TestController extends Controller
{
    public function index($action=null){
        //$minutes=Carbon::now()->addMinutes(10);
        //Cache::put('cachetest', '1', $minutes);
        Cache::flush();
    }
}
