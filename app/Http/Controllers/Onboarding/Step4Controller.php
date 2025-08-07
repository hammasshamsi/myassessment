<?php

namespace App\Http\Controllers\Onboarding;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Step4Controller extends Controller
{
    public function show(){
        return view('onboarding.step4');
    }
}
