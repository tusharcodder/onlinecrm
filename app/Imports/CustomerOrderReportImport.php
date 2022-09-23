<?php

namespace App\Imports;

use App\CustomerOrder;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Illuminate\Support\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use App\Common;
use DB;

HeadingRowFormatter::default('none');
class CustomerOrderReportImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
{
	
	use Importable;
	private $rows = 0;
	
	// request constructor
	public function __construct($request)
    {
		// get form request value
		$this->request = $request;
    }
	
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
		$importtype = $this->request['importtype'];
		++$this->rows;
		
		// current login id
		$user = Auth::user();
		$uid = $user->id;
		$customerdata = CustomerOrder::where('order_id', '=', strval($row['order-id']))
					->where('order_item_id', '=', strval($row['order-item-id']))->get();
		
		if(count($customerdata) > 0){
			
			//update shipqty into the quantity_to_be_shipped column and price
			DB::table('customer_orders')
			->where('order_id', strval($row['order-id']))
			->where('order_item_id', strval($row['order-item-id']))
			->update([
				'currency' => strval($row['currency']),
				'item_price' => strval($row['item-price']),
				'item_tax' => strval($row['item-tax']),
				'sales_channel' => strval($row['sales-channel']),
				'earliest_ship_date' => strval($row['earliest-ship-date']),
				'latest_ship_date' => strval($row['latest-ship-date']),
				'earliest_delivery_date' => strval($row['earliest-delivery-date']),
				'latest_delivery_date' => strval($row['latest-delivery-date']),
				'updated_by' => $uid,
				'updated_at' => date('Y-m-d H:i:s'),
			]);

		}
        return null;
    }
	
	public function headingRow(): int
    {
        return 1;
    }
	
	public function batchSize(): int
    {
        return 1000;
    }
    
    public function chunkSize(): int
    {
        return 1000;
    }
	
	public function getRowCount(): int
    {
        return $this->rows;
    }
}
