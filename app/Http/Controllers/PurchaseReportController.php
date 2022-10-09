<?php

namespace App\Http\Controllers;
use App\Vendor;
use Illuminate\Http\Request;
use DB;
use App\Exports\PurchaseReportExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Support\Collection;

class PurchaseReportController extends Controller
{
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:purchase-report', ['only' => ['index']]);
		$this->middleware('permission:download-purchase-report', ['only' => ['export']]);
    }
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //get the max priority
        $maxpriority = Vendor::max('priority');     
		$tjw_stock_ind = DB::table('warehouse_stocks')->select('isbn13','quantity')->where('warehouse_stocks.warehouse_id','1')->get();
        //create empty array  
		$purchaseorders = [];
		$isbn_stck = array();
		if(!empty($tjw_stock_ind)){
			foreach($tjw_stock_ind as $stock)
			{
				$isbn_stck[$stock->isbn13] = $stock; 
			}
		}
        //get details
		$result = DB::table('customer_orders')     
        ->leftjoin('skudetails','skudetails.sku_code','customer_orders.sku') 
		->leftjoin('book_details','book_details.isbnno','skudetails.isbn13')  
		->select('customer_orders.sku','customer_orders.product_name','skudetails.isbn13'
		,'book_details.name','book_details.author', 'book_details.publisher' ,'skudetails.type',DB::raw(" 0 as quantity ")  ,    
		DB::raw("sum(customer_orders.quantity_to_ship) as cust_qty"),DB::raw("IFNULL( ( SELECT sum(INO.quantity_to_be_shipped) from customer_orders INO where INO.warehouse_country_code = 'IN' and INO.sku = customer_orders.sku and INO.quantity_to_be_shipped > 0 GROUP by INO.sku ), 0) as block_qty"))
		->where('customer_orders.quantity_to_ship', '!=', 0)  
		//->where('skudetails.type', 'Single')   
		->where(function($query){
			$query->whereNull('customer_orders.warehouse_country_code')
			->orwhere('customer_orders.warehouse_country_code','IN');
		})  
		->groupby('skudetails.isbn13')->get();
		
		// echo '<pre>';
		// print_r($result);
		// echo '<pre>';exit; 
		
	//	DB::statement('update vendor_stocks v1 inner join vendor_stocks v2 on v1.id=v2.id set v1.temp_quantity = v2.quantity'); 

		if($result->count() > 0){
			foreach($result as $value){
				$value->quantity = ((int)$value->cust_qty - (int)$value->block_qty);
				if($value->quantity > 0){
					
					if($value->type == 'Single')
					{
						
						//check isbn in warehouse 
						if(array_key_exists($value->isbn13,$isbn_stck) && $isbn_stck[$value->isbn13]->quantity > 0)
						{							
							//get current stock 
							$isbn_stck[$value->isbn13]->quantity = ((int)$isbn_stck[$value->isbn13]->quantity-(int)$value->block_qty);
							//check again isbn have quantity in stock
							if($isbn_stck[$value->isbn13]->quantity > 0){
								//echo 'step-5  Isbn '.$value->isbn13. '';
								//check customer order quantity is greater from current stock 
								if((int)$value->quantity >= (int)$isbn_stck[$value->isbn13]->quantity)
								{
									//echo 'step-6  Isbn '.$value->isbn13. '';
									//get current customer order
									$value->quantity = ((int)$value->quantity - (int)$isbn_stck[$value->isbn13]->quantity);
									//set 0 to isbn current stock
									$isbn_stck[$value->isbn13]->quantity = 0;

									if($value->quantity > 0)
									{
										
										$flag = 0; 
										$istrue = false;
										$remainquantity = $value->quantity;//set the quantity
										$updatequantity = 0;
										//get the vendor's stock priority wise	
										$venderdetails = DB::table('vendor_stocks')
										->join('vendors','vendors.id','vendor_stocks.vendor_id')
										->select('vendor_stocks.vendor_id','vendors.name','author','publisher','vendor_stocks.quantity','price')  
										//->where('vendors.priority',$i)
										->where('vendor_stocks.isbnno',$value->isbn13)
										->where('vendor_stocks.quantity','>',0)
										->orderBy('vendors.priority','asc')
										->get();

										//set the book name
										$bookname = (!empty($value->name)) ? $value->name : $value->product_name;	
										if($venderdetails->count() > 0){							
											//set other vendors details	
											$vendordata = '';						
											for($i = 1; $i<= $venderdetails->count()-1; $i++){
												$vendordata = $vendordata.''. $venderdetails[$i]->name.'-'.$venderdetails[$i]->price.'-'.$venderdetails[$i]->quantity.',';
											}                            						
											$flag = 1;	
											
											$dataarray = array(
												'Sku'=>$value->sku,
												"isbn13"=>$value->isbn13,
												"cisbn13"=>$value->isbn13,
												'book'=> $bookname ,
												'mrp'=>$venderdetails[0]->price,
												'author'=>$venderdetails[0]->author,
												'publisher'=>$venderdetails[0]->publisher,
												'New'=>$remainquantity,
												'quantity'=>$venderdetails[0]->quantity,
												'vendor_name'=>$venderdetails[0]->name,
												'vendordata'=>$vendordata,
											);
											$purchaseorders[] = $dataarray;
													
										}
										else{
											//get the vendor's stock based on isbn(zero quantity)
											$venderdetails = DB::table('vendor_stocks')
											->join('vendors','vendors.id','vendor_stocks.vendor_id')
											->select('vendor_stocks.vendor_id','vendors.name','author','publisher','vendor_stocks.quantity','price')  
											//->where('vendors.priority',$i)
											->where('vendor_stocks.isbnno',$value->isbn13)
											//->where('vendor_stocks.quantity','>',0)
											->orderBy('vendors.priority','asc')
											->get();
											if($venderdetails->count() > 0){							
												//set other vendors details	
												$vendordata = '';						
												for($i = 0; $i<= $venderdetails->count()-1; $i++){
													$vendordata = $vendordata.''. $venderdetails[$i]->name.'-'.$venderdetails[$i]->price.'-'.$venderdetails[$i]->quantity.',';
												}     
												$dataarray = array(
													'Sku'=>$value->sku,
													"isbn13"=>$value->isbn13,
													"cisbn13"=>$value->isbn13,
													'book'=> $bookname ,
													'mrp'=>'NA',
													'author'=>'NA',
													'publisher'=>'NA',
													'New'=>$remainquantity,
													'quantity'=>'NA',
													'vendor_name'=>'NA',
													'vendordata'=>$vendordata,
												);
												$purchaseorders[] = $dataarray;
											}else{//isbn not in any vendor stock
												$dataarray = array(
													'Sku'=>$value->sku,
													"isbn13"=>$value->isbn13,
													"cisbn13"=>$value->isbn13,
													'book'=> $bookname ,
													'mrp'=>'NA',
													'author'=>'NA',
													'publisher'=>'NA',
													'New'=>$remainquantity,
													'quantity'=>'NA',
													'vendor_name'=>'NA',
													'vendordata'=>'NA',
												);
												$purchaseorders[] = $dataarray;
											}
												
										}
									}
								}
								else{
									
									//update current stock after fullfill customer order
									$isbn_stck[$value->isbn13]->quantity = ((int)$isbn_stck[$value->isbn13]->quantity-(int)$value->quantity);
									
								}
							}
							else{//when tjw stock is empty
								$flag = 0; 
								$istrue = false;
								$remainquantity = $value->quantity;//set the quantity
								$updatequantity = 0;
								//get the vendor's stock priority wise	
								$venderdetails = DB::table('vendor_stocks')
								->join('vendors','vendors.id','vendor_stocks.vendor_id')
								->select('vendor_stocks.vendor_id','vendors.name','author','publisher','vendor_stocks.quantity','price')  
								//->where('vendors.priority',$i)
								->where('vendor_stocks.isbnno',$value->isbn13)
								->where('vendor_stocks.quantity','>',0)
								->orderBy('vendors.priority','asc')
								->get();

								//set the book name
								$bookname = (!empty($value->name)) ? $value->name : $value->product_name;	
								if($venderdetails->count() > 0){							
									//set other vendors details	
									$vendordata = '';						
									for($i = 1; $i<= $venderdetails->count()-1; $i++){
										$vendordata = $vendordata.''. $venderdetails[$i]->name.'-'.$venderdetails[$i]->price.'-'.$venderdetails[$i]->quantity.',';
									}                            						
									$flag = 1;	
									
									$dataarray = array(
										'Sku'=>$value->sku,
										"isbn13"=>$value->isbn13,
										"cisbn13"=>$value->isbn13,
										'book'=> $bookname ,
										'mrp'=>$venderdetails[0]->price,
										'author'=>$venderdetails[0]->author,
										'publisher'=>$venderdetails[0]->publisher,
										'New'=>$remainquantity,
										'quantity'=>$venderdetails[0]->quantity,
										'vendor_name'=>$venderdetails[0]->name,
										'vendordata'=>$vendordata,
									);
									$purchaseorders[] = $dataarray;
											
								}
								else{
									//get the vendor's stock based on isbn(zero quantity)	
									$venderdetails = DB::table('vendor_stocks')
									->join('vendors','vendors.id','vendor_stocks.vendor_id')
									->select('vendor_stocks.vendor_id','vendors.name','author','publisher','vendor_stocks.quantity','price')  
									//->where('vendors.priority',$i)
									->where('vendor_stocks.isbnno',$value->isbn13)
									//->where('vendor_stocks.quantity','>',0)
									->orderBy('vendors.priority','asc')
									->get();
									if($venderdetails->count() > 0){							
										//set other vendors details	
										$vendordata = '';						
										for($i = 0; $i<= $venderdetails->count()-1; $i++){
											$vendordata = $vendordata.''. $venderdetails[$i]->name.'-'.$venderdetails[$i]->price.'-'.$venderdetails[$i]->quantity.',';
										}     
										$dataarray = array(
											'Sku'=>$value->sku,
											"isbn13"=>$value->isbn13,
											"cisbn13"=>$value->isbn13,
											'book'=> $bookname ,
											'mrp'=>'NA',
											'author'=>'NA',
											'publisher'=>'NA',
											'New'=>$remainquantity,
											'quantity'=>'NA',
											'vendor_name'=>'NA',
											'vendordata'=>$vendordata,
										);
										$purchaseorders[] = $dataarray;
									}else{//isbn not in any vendor stock
										$dataarray = array(
											'Sku'=>$value->sku,
											"isbn13"=>$value->isbn13,
											"cisbn13"=>$value->isbn13,
											'book'=> $bookname ,
											'mrp'=>'NA',
											'author'=>'NA',
											'publisher'=>'NA',
											'New'=>$remainquantity,
											'quantity'=>'NA',
											'vendor_name'=>'NA',
											'vendordata'=>'NA',
										);
										$purchaseorders[] = $dataarray;
									}
								}
							}
						}//isbn not in warehouse and check to vendor stock
						else{
							$flag = 0; 
							$istrue = false;
							$remainquantity = $value->quantity;//set the quantity
							$updatequantity = 0;
							//get the vendor's stock priority wise	
							$venderdetails = DB::table('vendor_stocks')
							->join('vendors','vendors.id','vendor_stocks.vendor_id')
							->select('vendor_stocks.vendor_id','vendors.name','author','publisher','vendor_stocks.quantity','price')  
							//->where('vendors.priority',$i)
							->where('vendor_stocks.isbnno',$value->isbn13)
							->where('vendor_stocks.quantity','>',0)
							->orderBy('vendors.priority','asc')
							->get();

							//set the book name
							$bookname = (!empty($value->name)) ? $value->name : $value->product_name;	
							if($venderdetails->count() > 0){							
								//set other vendors details	
								$vendordata = '';						
								for($i = 1; $i<= $venderdetails->count()-1; $i++){
									$vendordata = $vendordata.''. $venderdetails[$i]->name.'-'.$venderdetails[$i]->price.'-'.$venderdetails[$i]->quantity.',';
								}                            						
								$flag = 1;	
								
								$dataarray = array(
									'Sku'=>$value->sku,
									"isbn13"=>$value->isbn13,
									"cisbn13"=>$value->isbn13,
									'book'=> $bookname ,
									'mrp'=>$venderdetails[0]->price,
									'author'=>$venderdetails[0]->author,
									'publisher'=>$venderdetails[0]->publisher,
									'New'=>$remainquantity,
									'quantity'=>$venderdetails[0]->quantity,
									'vendor_name'=>$venderdetails[0]->name,
									'vendordata'=>$vendordata,
								);
								$purchaseorders[] = $dataarray;
										
							}
							else{
								//get all vendors stock based on isbn(zero quantity) 	
								$venderdetails = DB::table('vendor_stocks')
								->join('vendors','vendors.id','vendor_stocks.vendor_id')
								->select('vendor_stocks.vendor_id','vendors.name','author','publisher','vendor_stocks.quantity','price')  
								//->where('vendors.priority',$i)
								->where('vendor_stocks.isbnno',$value->isbn13)
								//->where('vendor_stocks.quantity','>',0)
								->orderBy('vendors.priority','asc')
								->get();
								if($venderdetails->count() > 0){							
									//set other vendors details	
									$vendordata = '';						
									for($i = 0; $i<= $venderdetails->count()-1; $i++){
										$vendordata = $vendordata.''. $venderdetails[$i]->name.'-'.$venderdetails[$i]->price.'-'.$venderdetails[$i]->quantity.',';
									}     
									$dataarray = array(
										'Sku'=>$value->sku,
										"isbn13"=>$value->isbn13,
										"cisbn13"=>$value->isbn13,
										'book'=> $bookname ,
										'mrp'=>'NA',
										'author'=>'NA',
										'publisher'=>'NA',
										'New'=>$remainquantity,
										'quantity'=>'NA',
										'vendor_name'=>'NA',
										'vendordata'=>$vendordata,
									);
									$purchaseorders[] = $dataarray;
								}else{
									$dataarray = array(
										'Sku'=>$value->sku,
										"isbn13"=>$value->isbn13,
										"cisbn13"=>$value->isbn13,
										'book'=> $bookname ,
										'mrp'=>'NA',
										'author'=>'NA',
										'publisher'=>'NA',
										'New'=>$remainquantity,
										'quantity'=>'NA',
										'vendor_name'=>'NA',
										'vendordata'=>'NA',
									);
									$purchaseorders[] = $dataarray;
								}
							}
						}
					}
					else{//step for Box Isbn
						$child_box_isbn = DB::table('box_child_isbns')
											->select('box_child_isbns.book_isbn13','book_details.name','book_details.author', 'book_details.publisher' )
											->leftjoin('box_parent_isbns','box_parent_isbns.id','=','box_child_isbns.box_isbn_id')
											->leftjoin('book_details','book_details.isbnno','box_child_isbns.book_isbn13')
											->where('box_parent_isbns.box_isbn13',$value->isbn13)
											->get();
											///echo '<pre>'; print_r($child_box_isbn);echo '</pre>';
											$remainquantity = $value->quantity;				
						if(!empty(count($child_box_isbn))){
							
							$bookname = '';
							$cisbn13 = '';
							$mrp = '';	
							$author	='';	
							$publisher ='';		
							$Newqty = '';	
							$quantity	 = '';
							$vendor_name = '';
							foreach($child_box_isbn as $child_isbn)
							{
								//$value->isbn13 = $child_isbn->book_isbn13;
								//check warehouse have this isbn
								if(array_key_exists($child_isbn->book_isbn13,$isbn_stck))
								{
									//check isbn have quantity in stock
									if($isbn_stck[$child_isbn->book_isbn13]->quantity > 0)
									{
										$isbn_stck[$child_isbn->book_isbn13]->quantity = ((int)$isbn_stck[$child_isbn->book_isbn13]->quantity-(int)$value->block_qty);	

										if($isbn_stck[$child_isbn->book_isbn13]->quantity > 0)
										{
											//check customer order quantity is greater from current stock 
											if((int)$value->quantity >= (int)$isbn_stck[$child_isbn->book_isbn13]->quantity)
											{
												//echo 'step-6  Isbn '.$value->isbn13. '';
												//get current customer order
												$value->quantity = ((int)$value->quantity - (int)$isbn_stck[$child_isbn->book_isbn13]->quantity);
												//set 0 to isbn current stock
												$isbn_stck[$child_isbn->book_isbn13]->quantity = 0;

												if($value->quantity > 0)
												{
													
													$flag = 0; 
													$istrue = false;
													$remainquantity = $value->quantity;//set the quantity
													$updatequantity = 0;
													//get the vendor's stock priority wise	
													$venderdetails = DB::table('vendor_stocks')
													->join('vendors','vendors.id','vendor_stocks.vendor_id')
													->select('vendor_stocks.vendor_id','vendors.name','author','publisher','vendor_stocks.quantity','price')  
													//->where('vendors.priority',$i)
													->where('vendor_stocks.isbnno',$child_isbn->book_isbn13)
													->where('vendor_stocks.quantity','>',0)
													->orderBy('vendors.priority','asc')
													->get();

													//set the book name
													$bookname = (!empty($child_isbn->name)) ? $child_isbn->name : $value->product_name;	
													if($venderdetails->count() > 0){							
														//set other vendors details	
														$vendordata = '';						
														for($i = 1; $i<= $venderdetails->count()-1; $i++){
															$vendordata = $vendordata.''. $venderdetails[$i]->name.'-'.$venderdetails[$i]->price.'-'.$venderdetails[$i]->quantity.',';
														}                            						
														$flag = 1;	
														
														$dataarray = array(
															'Sku'=>$value->sku,
															"isbn13"=>$value->isbn13,
															"cisbn13"=>$child_isbn->book_isbn13,
															'book'=> $bookname ,
															'mrp'=>$venderdetails[0]->price,
															'author'=>$venderdetails[0]->author,
															'publisher'=>$venderdetails[0]->publisher,
															'New'=>$remainquantity,
															'quantity'=>$venderdetails[0]->quantity,
															'vendor_name'=>$venderdetails[0]->name,
															'vendordata'=>$vendordata,
														);
														$purchaseorders[] = $dataarray;
																
													}
													else{
														//get the vendor's stock priority wise	
															$venderdetails = DB::table('vendor_stocks')
														->join('vendors','vendors.id','vendor_stocks.vendor_id')
														->select('vendor_stocks.vendor_id','vendors.name','author','publisher','vendor_stocks.quantity','price')  
														//->where('vendors.priority',$i)
														->where('vendor_stocks.isbnno',$child_isbn->book_isbn13)
														//->where('vendor_stocks.quantity','>',0)
														->orderBy('vendors.priority','asc')
														->get();
														$bookname = (!empty($child_isbn->name)) ? $child_isbn->name : $value->product_name;	
														if($venderdetails->count() > 0){							
															//set other vendors details	
															$vendordata = '';						
															for($i = 0; $i<= $venderdetails->count()-1; $i++){
																$vendordata = $vendordata.''. $venderdetails[$i]->name.'-'.$venderdetails[$i]->price.'-'.$venderdetails[$i]->quantity.',';
															}                            						
															$flag = 1;	
															
															$dataarray = array(
																'Sku'=>$value->sku,
																"isbn13"=>$value->isbn13,
																"cisbn13"=>$child_isbn->book_isbn13,
																'book'=> $bookname ,
																'mrp'=>'NA',
																'author'=>'NA',
																'publisher'=>'NA',
																'New'=>$remainquantity,
																'quantity'=>'NA',
																'vendor_name'=>'NA',
																'vendordata'=>$vendordata,
															);
															$purchaseorders[] = $dataarray;
																	
														}
														else{
															$dataarray = array(
																'Sku'=>$value->sku,
																"isbn13"=>$value->isbn13,
																"cisbn13"=>$child_isbn->book_isbn13,
																'book'=> $bookname ,
																'mrp'=>'NA',
																'author'=>'NA',
																'publisher'=>'NA',
																'New'=>$remainquantity,
																'quantity'=>'NA',
																'vendor_name'=>'NA',
																'vendordata'=>'NA',
															);
															$purchaseorders[] = $dataarray;
														}
													}
												}
											}
											else{
												
												//update current stock after fullfill customer order
												$isbn_stck[$child_isbn->book_isbn13]->quantity = ((int)$isbn_stck[$child_isbn->book_isbn13]->quantity-(int)$value->quantity);
												
											}
										}
										else{//tjw stock empty and check vendor stock
											$flag = 0; 
											$istrue = false;
											$remainquantity = $value->quantity;//set the quantity
											$updatequantity = 0;
											//get the vendor's stock priority wise	
											$venderdetails = DB::table('vendor_stocks')
											->join('vendors','vendors.id','vendor_stocks.vendor_id')
											->select('vendor_stocks.vendor_id','vendors.name','author','publisher','vendor_stocks.quantity','price')  
											//->where('vendors.priority',$i)
											->where('vendor_stocks.isbnno',$child_isbn->book_isbn13)
											->where('vendor_stocks.quantity','>',0)
											->orderBy('vendors.priority','asc')
											->get();
		
											//set the book name
											$bookname = (!empty($child_isbn->name)) ? $child_isbn->name : $value->product_name;	
											if($venderdetails->count() > 0){							
												//set other vendors details	
												$vendordata = '';						
												for($i = 1; $i<= $venderdetails->count()-1; $i++){
													$vendordata = $vendordata.''. $venderdetails[$i]->name.'-'.$venderdetails[$i]->price.'-'.$venderdetails[$i]->quantity.',';
												}                            						
												$flag = 1;	
												
												$dataarray = array(
													'Sku'=>$value->sku,
													"isbn13"=>$value->isbn13,
													"cisbn13"=>$child_isbn->book_isbn13,
													'book'=> $bookname ,
													'mrp'=>$venderdetails[0]->price,
													'author'=>$venderdetails[0]->author,
													'publisher'=>$venderdetails[0]->publisher,
													'New'=>$remainquantity,
													'quantity'=>$venderdetails[0]->quantity,
													'vendor_name'=>$venderdetails[0]->name,
													'vendordata'=>$vendordata,
												);
												$purchaseorders[] = $dataarray;
														
											}
											else{//isbn with empty quantity in vendor stock
												
												$venderdetails = DB::table('vendor_stocks')
												->join('vendors','vendors.id','vendor_stocks.vendor_id')
												->select('vendor_stocks.vendor_id','vendors.name','author','publisher','vendor_stocks.quantity','price')  
												//->where('vendors.priority',$i)
												->where('vendor_stocks.isbnno',$child_isbn->book_isbn13)
												//->where('vendor_stocks.quantity','>',0)
												->orderBy('vendors.priority','asc')
												->get();
												$bookname = (!empty($child_isbn->name)) ? $child_isbn->name : $value->product_name;	
												if($venderdetails->count() > 0){							
													//set other vendors details	
													$vendordata = '';						
													for($i = 0; $i<= $venderdetails->count()-1; $i++){
														$vendordata = $vendordata.''. $venderdetails[$i]->name.'-'.$venderdetails[$i]->price.'-'.$venderdetails[$i]->quantity.',';
													}                            						
													$flag = 1;	
													
													$dataarray = array(
														'Sku'=>$value->sku,
														"isbn13"=>$value->isbn13,
														"cisbn13"=>$child_isbn->book_isbn13,
														'book'=> $bookname ,
														'mrp'=>'NA',
														'author'=>'NA',
														'publisher'=>'NA',
														'New'=>$remainquantity,
														'quantity'=>'NA',
														'vendor_name'=>'NA',
														'vendordata'=>$vendordata,
													);
													$purchaseorders[] = $dataarray;
															
												}
												else{//when isbn not found in any vendor stock
													$dataarray = array(
														'Sku'=>$value->sku,
														"isbn13"=>$value->isbn13,
														"cisbn13"=>$child_isbn->book_isbn13,
														'book'=> $bookname ,
														'mrp'=>'NA',
														'author'=>'NA',
														'publisher'=>'NA',
														'New'=>$remainquantity,
														'quantity'=>'NA',
														'vendor_name'=>'NA',
														'vendordata'=>'NA',
													);
													$purchaseorders[] = $dataarray;
												}
											}
										}
									}
									else{//tjw stock is empty and check vendor stock
										$flag = 0; 
										$istrue = false;
										$remainquantity = $value->quantity;//set the quantity
										$updatequantity = 0;
										//get the vendor's stock priority wise	
										$venderdetails = DB::table('vendor_stocks')
										->join('vendors','vendors.id','vendor_stocks.vendor_id')
										->select('vendor_stocks.vendor_id','vendors.name','author','publisher','vendor_stocks.quantity','price')  
										//->where('vendors.priority',$i)
										->where('vendor_stocks.isbnno',$child_isbn->book_isbn13)
										->where('vendor_stocks.quantity','>',0)
										->orderBy('vendors.priority','asc')
										->get();
	
										//set the book name
										$bookname = (!empty($child_isbn->name)) ? $child_isbn->name : $value->product_name;
										if($venderdetails->count() > 0){							
											//set other vendors details	
											$vendordata = '';						
											for($i = 1; $i<= $venderdetails->count()-1; $i++){
												$vendordata = $vendordata.''. $venderdetails[$i]->name.'-'.$venderdetails[$i]->price.'-'.$venderdetails[$i]->quantity.',';
											}                            						
											$flag = 1;	
											
											$dataarray = array(
												'Sku'=>$value->sku,
												"isbn13"=>$value->isbn13,
												"cisbn13"=>$child_isbn->book_isbn13,
												'book'=> $bookname ,
												'mrp'=>$venderdetails[0]->price,
												'author'=>$venderdetails[0]->author,
												'publisher'=>$venderdetails[0]->publisher,
												'New'=>$remainquantity,
												'quantity'=>$venderdetails[0]->quantity,
												'vendor_name'=>$venderdetails[0]->name,
												'vendordata'=>$vendordata,
											);
											$purchaseorders[] = $dataarray;
													
										}
										else{//isbn with empty quantity in vendor stock
											//get the vendor's stock priority wise	
											$venderdetails = DB::table('vendor_stocks')
											->join('vendors','vendors.id','vendor_stocks.vendor_id')
											->select('vendor_stocks.vendor_id','vendors.name','author','publisher','vendor_stocks.quantity','price')  
											//->where('vendors.priority',$i)
											->where('vendor_stocks.isbnno',$child_isbn->book_isbn13)
											//->where('vendor_stocks.quantity','>',0)
											->orderBy('vendors.priority','asc')
											->get();
											$bookname = (!empty($child_isbn->name)) ? $child_isbn->name : $value->product_name;	
											if($venderdetails->count() > 0){							
												//set other vendors details	
												$vendordata = '';						
												for($i = 0; $i<= $venderdetails->count()-1; $i++){
													$vendordata = $vendordata.''. $venderdetails[$i]->name.'-'.$venderdetails[$i]->price.'-'.$venderdetails[$i]->quantity.',';
												}                            						
												$flag = 1;	
												
												$dataarray = array(
													'Sku'=>$value->sku,
													"isbn13"=>$value->isbn13,
													"cisbn13"=>$child_isbn->book_isbn13,
													'book'=> $bookname ,
													'mrp'=>'NA',
													'author'=>'NA',
													'publisher'=>'NA',
													'New'=>$remainquantity,
													'quantity'=>'NA',
													'vendor_name'=>'NA',
													'vendordata'=>$vendordata,
												);
												$purchaseorders[] = $dataarray;
														
											}
											else{//when isbn not found in any vendor stock
												$dataarray = array(
													'Sku'=>$value->sku,
													"isbn13"=>$value->isbn13,
													"cisbn13"=>$child_isbn->book_isbn13,
													'book'=> $bookname ,
													'mrp'=>'NA',
													'author'=>'NA',
													'publisher'=>'NA',
													'New'=>$remainquantity,
													'quantity'=>'NA',
													'vendor_name'=>'NA',
													'vendordata'=>'NA',
												);
												$purchaseorders[] = $dataarray;
											}
										}
									}
								}
								else{
									//$value->isbn13 = $child_isbn->book_isbn13;
									$flag = 0; 
									$istrue = false;
									$remainquantity = $value->quantity;//set the quantity
									$updatequantity = 0;
									//get the vendor's stock priority wise	
									$venderdetails = DB::table('vendor_stocks')
									->join('vendors','vendors.id','vendor_stocks.vendor_id')
									->select('vendor_stocks.vendor_id','vendors.name','author','publisher','vendor_stocks.quantity','price')  
									//->where('vendors.priority',$i)
									->where('vendor_stocks.isbnno',$child_isbn->book_isbn13)
									->where('vendor_stocks.quantity','>',0)
									->orderBy('vendors.priority','asc')
									->get();

									//set the book name
									$bookname = (!empty($child_isbn->name)) ? $child_isbn->name : $value->product_name;	
									if($venderdetails->count() > 0){							
										//set other vendors details	
										$vendordata = '';						
										for($i = 0; $i<= $venderdetails->count()-1; $i++){
											$vendordata = $vendordata.''. $venderdetails[$i]->name.'-'.$venderdetails[$i]->price.'-'.$venderdetails[$i]->quantity.',';
										}                            						
										$flag = 1;	
										
										$dataarray = array(
											'Sku'=>$value->sku,
											"isbn13"=>$value->isbn13,
											"cisbn13"=>$child_isbn->book_isbn13,
											'book'=> $bookname ,
											'mrp'=>$venderdetails[0]->price,
											'author'=>$venderdetails[0]->author,
											'publisher'=>$venderdetails[0]->publisher,
											'New'=>$remainquantity,
											'quantity'=>$venderdetails[0]->quantity,
											'vendor_name'=>$venderdetails[0]->name,
											'vendordata'=>$vendordata,
										);
										$purchaseorders[] = $dataarray;
												
									}
									else{
										//isbn with empty quantity in vendor stock	
										$venderdetails = DB::table('vendor_stocks')
										->join('vendors','vendors.id','vendor_stocks.vendor_id')
										->select('vendor_stocks.vendor_id','vendors.name','author','publisher','vendor_stocks.quantity','price')  
										//->where('vendors.priority',$i)
										->where('vendor_stocks.isbnno',$child_isbn->book_isbn13)
										//->where('vendor_stocks.quantity','>',0)
										->orderBy('vendors.priority','asc')
										->get();
										$bookname = (!empty($child_isbn->name)) ? $child_isbn->name : $value->product_name;	
										if($venderdetails->count() > 0){							
											//set other vendors details	
											$vendordata = '';						
											for($i = 0; $i<= $venderdetails->count()-1; $i++){
												$vendordata = $vendordata.''. $venderdetails[$i]->name.'-'.$venderdetails[$i]->price.'-'.$venderdetails[$i]->quantity.',';
											}                            						
											$flag = 1;	
											
											$dataarray = array(
												'Sku'=>$value->sku,
												"isbn13"=>$value->isbn13,
												"cisbn13"=>$child_isbn->book_isbn13,
												'book'=> $bookname ,
												'mrp'=>'NA',
												'author'=>'NA',
												'publisher'=>'NA',
												'New'=>$remainquantity,
												'quantity'=>'NA',
												'vendor_name'=>'NA',
												'vendordata'=>$vendordata,
											);
											$purchaseorders[] = $dataarray;
													
										}
										else{//when isbn not found in any vendor stock
											$dataarray = array(
												'Sku'=>$value->sku,
												"isbn13"=>$value->isbn13,
												"cisbn13"=>$child_isbn->book_isbn13,
												'book'=> $bookname ,
												'mrp'=>'NA',
												'author'=>'NA',
												'publisher'=>'NA',
												'New'=>$remainquantity,
												'quantity'=>'NA',
												'vendor_name'=>'NA',
												'vendordata'=>'NA',
											);
											$purchaseorders[] = $dataarray;
										}
									}
								}
							}	
						}
						else{//when box isbn not found in system
							$dataarray = array(
								'Sku'=>$value->sku,
								"isbn13"=>$value->isbn13,
								"cisbn13"=>'NA',
								'book'=> $value->product_name,
								'mrp'=>'NA',
								'author'=>'NA',
								'publisher'=>'NA',
								'New'=>$remainquantity,
								'quantity'=>'NA',
								'vendor_name'=>'NA',
								'vendordata'=>'NA',
							);
							$purchaseorders[] = $dataarray;
						}				
					}
				}
			}
					
		} 
//exit;
		$purchaseorders = (new Collection($purchaseorders))->sortBy('vendor_name')->paginate(1000)->setPath('');
		return view('reports.purchasereport',compact('purchaseorders'));

	}	

	//download excel
	public function export(Request $request) 
    {				
		return Excel::download(new PurchaseReportExport($request), "PurchaseReport.".$request['exporttype']);	
    }  

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
	
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\VendorStock  $vendorStock
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\VendorStock  $vendorStock
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
	}

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\VendorStock  $vendorStock
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
		
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\VendorStock  $vendorStock
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
 		// 		
    }	

	public function getPurchaseOrderDetails($value)
	{
				
	}

}