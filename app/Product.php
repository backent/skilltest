<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'produk';

    protected $guarded = ['created_at', 'updated_at'];

    public function getImageAttribute($value) {
    	return $value;
    }

}
