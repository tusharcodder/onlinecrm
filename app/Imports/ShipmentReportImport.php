<?php

namespace App\Imports;

use App\CustomerOrder;
use App\OrderTrack;
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
class ShipmentReportImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
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
		++$this->rows;
		
		// current login id
		$user = Auth::user();
		$uid = $user->id;
		
		$row['order_item_id'] = str_replace('"', "", strval($row['order_item_id']));
		$row['isbn13'] = str_replace('"', "", strval($row['isbn13']));
		$row['bisbn'] = str_replace('"', "", strval($row['bisbn']));
		$row['rack_details'] = str_replace('"', "", strval($row['rack_details']));
		$row['shipper_tracking_id'] = str_replace('"', "", strval($row['shipper_tracking_id']));
		
		if(empty(strval($row['shipper_tracking_id'])) || is_null(strval($row['shipper_tracking_id'])) || strval($row['shipper_tracking_id']) == "#VALUE!" || strval($row['shipper_tracking_id']) == "#N/A") // not insert blank track id data
			return null;
		
		$row['quantity'] = empty($row['quantity']) ? 0 : strval($row['quantity']);
		
		// check duplicate track exists or not
		$trackdata = OrderTrack::where('order_id', '=', strval($row['order_id']))
			->where('order_item_id', '=', strval($row['order_item_id']))
			->where('shipper_tracking_id', '=', strval($row['shipper_tracking_id']))
			->get();
		if(!empty(count($trackdata))) // not inserted duplicated data
			return null;
			
		$customerdata = CustomerOrder::where('order_id', '=', strval($row['order_id']))
			->where('order_item_id', '=', strval($row['order_item_id']))
			->where('sku', '=', strval($row['sku']))
			->get();
		
		if(!empty(count($customerdata))){
			foreach($customerdata as $key=>$val){
				
				$val->quantity_shipped = empty($val->quantity_shipped) ? 0 : $val->quantity_shipped;
				$val->quantity_to_ship = empty($val->quantity_to_ship) ? 0 : $val->quantity_to_ship;
				
				$shippedqty = $val->quantity_shipped + strval($row['quantity']);
				$qtytoship = $val->quantity_to_ship - strval($row['quantity']);
				$qtytobeship = $val->quantity_to_be_shipped - strval($row['quantity']);
				$qtytobeship = empty($qtytobeship) ? 0 : $qtytobeship;
				
				//update shipqty into the quantity_to_be_shipped column and price
				DB::table('customer_orders')
				->where('order_id', strval($val->order_id))
				->where('order_item_id', strval($val->order_item_id))
				->where('sku', strval($val->sku))
				->update([
					'tracking_number' => strval($row['shipper_tracking_id']),
					'quantity_to_be_shipped' => $qtytobeship,
					'quantity_to_ship' => $qtytoship,
					'quantity_shipped' => $shippedqty,
					'price' => strval($row['price']),
					'shipping_price' => strval($row['shipping_price']),
					'selling_price' => strval($row['selling_price']),
				]);
			}
		}

		// insert and update product image path in product image table
        return new OrderTrack([
            'price' => strval($row['price']),
            'selling_price' => strval($row['selling_price']),
            'shipping_price' => strval($row['shipping_price']),
            'order_id' => strval($row['order_id']),
			'order_item_id' => strval($row['order_item_id']),
			'sku' => strval($row['sku']),
			'isbnno' => strval($row['isbn13']),
			'shipper_book_isbn' => strval($row['bisbn']),
			'rack_details' => strval($row['rack_details']),
			'warehouse_id' => strval($row['warehouse_id']),
			'warehouse_name' => strval($row['warehouse']),
			'box_shipper_id' => strval($row['box_shipper_id']),
			'shipper_tracking_id' => strval($row['shipper_tracking_id']),
			'box_id' => strval($row['box_id']),
			'shipper_id' => strval($row['shipper_id']),
			'shipment_date' => strval($row['shipment_date']),
			'quantity_shipped' => strval($row['quantity']),
			'ncp' => strval($row['ncp']),
			'created_by' => $uid,
			'updated_by' => $uid
        ]);
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
