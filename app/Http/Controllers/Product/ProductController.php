<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Product;

use App\Support\Json;
use Illuminate\Support\Facades\Storage;
use Image;

class ProductController extends Controller
{
    //

    public function get(Request $request, $key = null) {
    	if ($key !== null) {
    		$product = Product::find($key);
    	} else {
    		$product = Product::all();
    	}

    	Json::set('records', $product);
    	return response()->json(Json::get(), 200);

    }

    public function store(Request $request) {
    	$data = $request->validate($this->rules());

    	$nameExist = Product::where('nama_produk', $data['nama_produk'])->first();
    	if (isset($nameExist)) {
    		Json::set('message', "Nama produk sudah digunakan");
    		return response()->json(Json::get(), 409);
    	}
    	
		$filePath = $this->saveImage($request->file('image'));
		$data['image'] = $filePath;
		$product = Product::create($data);
		Json::set('records', $product);
    	return response()->json(Json::get(), 201);
    }

    public function update(Request $request, $key) {
    	$data = $request->validate($this->rules());

    	$nameExist = Product::where('nama_produk', $data['nama_produk'])->first();
    	if (isset($nameExist)) {
    		Json::set('message', "Nama produk sudah digunakan");
    		return response()->json(Json::get(), 409);
    	}

    	$product = Product::where('id', $key)->first();
    	if (!isset($product)) {
    		Json::set('message', "Not Found");
    		return response()->json(Json::get(), 404);
    	}

    	$filePath = $this->saveImage($request->file('image'));
    	$data['image'] = $filePath;
    	$product->image = $data['image'];
    	$product->nama_produk = $data['nama_produk'];
    	$product->harga = $data['harga'];
    	$product->stock = $data['stock'];
    	$product->save();

    	Json::set('records', $product);
    	return response()->json(Json::get(), 200);
    }

    public function delete($key) {
    	$product = Product::where('id', $key)->delete();

    	return response()->json([], 200);
    }

    private function saveImage($image) {
    	$ext = $image->getClientOriginalExtension();
    	$fileName   = time() . '.' . $ext;
    	$folder = 'images/';

        $img = Image::make($image)->stream($ext, 70);
        Storage::disk('local')->put('public/' . $folder . $fileName, $img, 'public');
        return 'storage/' . $folder . $fileName;
    }

    private function rules() {
    	$rules = [
    		'image' => 'required|mimes:jpeg,jpg,png',
    		'nama_produk' => 'required',
    		'stock' => 'required|numeric',
    		'harga' => 'required|numeric'
    	];

    	return $rules;
    }
}
