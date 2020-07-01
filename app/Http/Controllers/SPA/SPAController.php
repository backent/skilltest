<?php

namespace App\Http\Controllers\SPA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SPAController extends Controller
{
   	public function index()
    {
    	return view('welcome');
    }
}
