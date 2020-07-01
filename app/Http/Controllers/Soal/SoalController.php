<?php

namespace App\Http\Controllers\Soal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Support\Json;
use App\Soal1;
use App\Soal2;

class SoalController extends Controller
{
    

    public function soal(Request $request, $nomor) {
    	
    	switch ($nomor) {
    		case 1:
    			$soal1 = new Soal1(1,5);
    			$result = $soal1->runSoal1();
    			break;
    		case 2:
    			$soal2 = new Soal2(1575250);
    			$result = $soal2->runSoal2();
    			break;
    		
    		default:
    			$result = null;
    			break;
    	}

    	Json::set('records', $result);
    	return response()->json(Json::get(), 200);
    }

}

    
