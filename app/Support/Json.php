<?php 
namespace App\Support;

/**
 * 
 */
class Json
{
	
	private static $response = [];


	public static function set($key, $val) {
		$init = new self();
		$init::$response[$key] = $val;
		return $init;
	}

	public static function get() {
		$init = new self();
		return $init::$response;
	}
}


 ?>