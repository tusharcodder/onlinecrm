<?php

namespace App\Exports;

use App\PriceInventory;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use DB;

//class CustomerOrderExport implements FromCollection, WithHeadings, WithMapping, WithDrawings
class UspsPriceExport implements FromView
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
    {	$finalstkarray = [];
        $queryforsinglestock = DB::table('order_tracking')->select('customer_orders.ship_postal_code','customer_orders.label_shipping_weight','usps_price_details.tracking_no')                              
                                ->join('usps_price_details','usps_price_details.tracking_no','=','order_tracking.shipper_tracking_id')
                                ->leftjoin('customer_orders','customer_orders.order_id','=','order_tracking.order_id')                               
								->groupBy('order_tracking.order_id')					 			
                                ->get();		
  
        foreach($queryforsinglestock as $val)
        {
            $usps_price_details = DB::table('usps_price_details')->where('tracking_no',$val->tracking_no)->get();
            $zone_code = substr($val->ship_postal_code,0,3);
           // echo $zone_code;
            $price = DB::table('usps_zone_price_list')->select('zone_price','zone_list.zone as name')
                        ->leftjoin('usps_zone','usps_zone.zone_id','usps_zone_price_list.zone_id')
                       ->leftjoin('zone_list','zone_list.id','usps_zone.zone_id')                       
                       ->where('usps_zone.zip_code',$zone_code)
                       ->where('usps_zone_price_list.lbs_wgt_from','<=',$val->label_shipping_weight)
                       ->where('usps_zone_price_list.lbs_wgt_to','>=',$val->label_shipping_weight)
                       ->get();
            if($price->count()> 0)  {     
                $dataarray = array( 
                    'tracking_no'   => $val->tracking_no,
                    'wgt'           => $usps_price_details[0]->volume_wgt,
                    'price'         => $usps_price_details[0]->price,
                    'package_wgt'   => $val->label_shipping_weight,
                    'Zone'          => $price[0]->name,
                    'zone_price'    => $price[0]->zone_price

                );

                $finalstkarray[] = $dataarray;
            }else{
                $dataarray = array( 
                    'tracking_no'   => $val->tracking_no,
                    'wgt'           => $usps_price_details[0]->volume_wgt,
                    'price'         => $usps_price_details[0]->price,
                    'package_wgt'   => $val->label_shipping_weight,
                    'Zone'          => '',
                    'zone_price'    => '',

                );

                $finalstkarray[] = $dataarray;
            }
            
        }
    //  echo '<pre>';  print_r($finalstkarray); echo '</pre>'; exit; 
 // exit;
        return view('reports.exportuspsprice', [
			'results' => $finalstkarray,			
		]);
    }
}
