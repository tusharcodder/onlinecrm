<?php

namespace App\Exports;

use App\PriceInventory;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use DB;

//class CustomerOrderExport implements FromCollection, WithHeadings, WithMapping, WithDrawings
class PriceInventoryExport implements FromView
{	
	/**
    * get request values
    */
	public function __construct($request)
    {
		// get form request value
		$this->request = $request;
    }
	
	/**
    * get values from view
    */
	public function view(): View
    {	$finalstkarray = array();
        $queryforsinglestock = DB::table('skudetails')->select('skudetails.sku_code','skudetails.isbn13'
								,DB::raw("(select IFNULL(SUM(quantity),0) from vendor_stocks where vendor_stocks.isbnno = skudetails.isbn13) as ven_stk"),
								DB::raw("(select IFNULL(SUM(quantity),0) from warehouse_stocks where warehouse_stocks.isbn13 = skudetails.isbn13 and warehouse_stocks.warehouse_id = 1) as wr_stk"))                              
                                ->where('skudetails.type','Single')								
                                ->groupBy('skudetails.sku_code')
								->groupBy('skudetails.isbn13')					 			
                                ->get();
		//echo '<pre>'; print_r($queryforsinglestock); echo'</pre>';exit;					
        $isbnarray = array();
  
        if(!empty($queryforsinglestock))
        {
            foreach($queryforsinglestock as $value)
            {
                $isbnarray[$value->isbn13] = $value;
            }  
        }                         
        //get from uploaded sheet
        $queryforpriceinv = PriceInventory::select('price_inventory.sku','skudetails.sku_code', 'skudetails.type','skudetails.isbn13') 
        ->leftJoin('skudetails','skudetails.sku_code','=', 'price_inventory.sku')  
		->whereIn('skudetails.type',array('Box','Single'))	
        ->orderBy('skudetails.type','asc')->get();
  
        foreach($queryforpriceinv as $val)
        {
           // echo 'helo';
            if($val->type == 'Box')
            {
                //child isbn details
                $queryforchildisbn = DB::table('box_child_isbns')->select('book_isbn13')
                                        ->leftjoin('box_parent_isbns','box_parent_isbns.id','=','box_child_isbns.box_isbn_id')
                                        ->where('box_parent_isbns.box_isbn13',$val->isbn13)
                                        ->get();
                if(!empty(count($queryforchildisbn)))   
                {
                    $tjwsktarray = array();
                    $venstkarray = array();
                    foreach($queryforchildisbn as $key=>$childval )
                    {
                         $childvendorstk =  array_key_exists($childval->book_isbn13,$isbnarray)?$isbnarray[$childval->book_isbn13]->ven_stk:0;
                         $childtjwstk =  array_key_exists($childval->book_isbn13,$isbnarray)?$isbnarray[$childval->book_isbn13]->wr_stk:0;
  
                         $tjwsktarray[] =  $childtjwstk;
                         $venstkarray[] =  $childvendorstk;
                    }
                  //  print_r($venstkarray) ;
                    $minvenstk = min($venstkarray);
                    $mintjwstk = min($tjwsktarray);
                  //  echo $minvenstk;
                  //  echo $mintjwstk;
                    $markqty = 0;
                    if($minvenstk < 10)
                        $markqty = 0;
                    else if($minvenstk < 15)
                        $markqty = 2; 
                    else if($minvenstk < 25)
                        $markqty = 3; 
                    else if($minvenstk < 50)
                        $markqty = 10;
                    else if($minvenstk < 100)
                        $markqty = 25;
                    else
                        $markqty = 40;        
                    
                    $finalstkarray[$val->sku] = (object)([
                            'sku'   => $val->sku,
                            'qty'   => ((int)$mintjwstk + (int)$markqty),
                    ]);  
                         
                    foreach($queryforchildisbn as $key=>$childval )
                    {                        
                        if(array_key_exists($childval->book_isbn13,$isbnarray)){
                            $isbnarray[$childval->book_isbn13]->ven_stk = $isbnarray[$childval->book_isbn13]->ven_stk - $minvenstk;
                            $isbnarray[$childval->book_isbn13]->wr_stk = $isbnarray[$childval->book_isbn13]->wr_stk - $mintjwstk;
                        }                         
                    }
  
                }                     
  
  
            }
            else if($val->type == 'Single')
            { 
                $minvenstk = array_key_exists($val->isbn13,$isbnarray)?$isbnarray[$val->isbn13]->ven_stk:0;
                $mintjwstk = array_key_exists($val->isbn13,$isbnarray)?$isbnarray[$val->isbn13]->wr_stk:0;
  
                $markqty = 0;
                if($minvenstk < 10)
                    $markqty = 0;
                else if($minvenstk < 15)
                    $markqty = 2; 
                else if($minvenstk < 25)
                    $markqty = 3; 
                else if($minvenstk < 50)
                    $markqty = 10;
                else if($minvenstk < 100)
                    $markqty = 25;
                else
                    $markqty = 40;        
                
                $finalstkarray[$val->sku_code] = (object)([
                    'sku'   => $val->sku_code,
                    'qty'   => ((int)$mintjwstk + (int)$markqty),
                ]);  
            }
            
        }
  
        return view('reports.exportpriceinventory', [
			'results' => $finalstkarray,			
		]);
    }
}
