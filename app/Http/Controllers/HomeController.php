<?php

namespace App\Http\Controllers;

use PHPUnit\Framework\MockObject\ReturnValueNotConfiguredException;

class HomeController extends Controller
{
    public function index(){
        return view('home');
    }

    public function profile(){
        return view('layouts.profile');
    }
}
