<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Common extends Model
{
    //add product image
	public function addProductImage($pcode, $storefilepath){
		//insert product code and image url into product_images table
		if(!empty($storefilepath)){
			// delete product code if exists
			DB::table('product_images')->where('product_code', $pcode)->delete();
			
			// insert new one
			$insertarray = array(
				'product_code'=>$pcode,
				'image_url'=>$storefilepath
			);
			$query = DB::table('product_images')->insert($insertarray);
		}
		return true;
	}
}