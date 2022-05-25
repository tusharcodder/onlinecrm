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
    {	
				
		//if($format == "withheading"){ // data with heading
			// get details of discounts from query
			$query = PriceInventory::select('price_inventory.sku',
            DB::raw("
                (
                    case when sum(vendor_stocks.quantity) < 10 THEN 0 else (
                        case when sum(vendor_stocks.quantity) < 15 THEN 2
                        else(
                            case when sum(vendor_stocks.quantity) < 25 THEN 3
                            else(
                                case when sum(vendor_stocks.quantity) < 50 THEN 10
                                else(
                                    case when sum(vendor_stocks.quantity) < 100 THEN 25 else 40 END
                                )END
                            )END
                        )END
                    )END
                )as 'market_qunatity'            
             "),
             DB::raw("sum(warehouse_stocks.quantity) as 'stock_qty'"),
             DB::raw("CASE WHEN sum(warehouse_stocks.quantity) > 0 THEN 2 ELSE (CASE WHEN sum(vendor_stocks.quantity) > 0 THEN 4 else 0 end) end as 'leadtime'")
        )->join('skudetails','skudetails.sku_code','=', 'price_inventory.sku')
        ->join('warehouse_stocks','warehouse_stocks.isbn13','=', 'skudetails.isbn13')
        ->join('vendor_stocks','vendor_stocks.isbnno','=', 'skudetails.isbn13')
        ->groupBy('price_inventory.sku');
			
				
			$results = $query->get();
		// }else // only data heading for format
		// 	$results = collect([]);
			
        return view('reports.exportpriceinventory', [
			'results' => $results,			
		]);
    }
}
