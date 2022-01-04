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
		
		$row['quantity'] = empty($row['quantity']) ? 0 : $row['quantity'];
		
		// check duplicate track exists or not
		$trackdata = OrderTrack::where('order_id', '=', $row['order_id'])
			->where('order_item_id', '=', $row['order_item_id'])
			->where('shipper_tracking_id', '=', $row['shipper_tracking_id'])
			->get();
		if(!empty(count($trackdata))) // not inserted duplicated data
			return '';
			
		$customerdata = CustomerOrder::where('order_id', '=', $row['order_id'])
			->where('order_item_id', '=', $row['order_item_id'])
			->where('sku', '=', $row['sku'])
			->get();
		
		if(!empty(count($customerdata))){
			foreach($customerdata as $key=>$val){
				
				$val->quantity_shipped = empty($val->quantity_shipped) ? 0 : $val->quantity_shipped;
				
				$val->quantity_to_ship = empty($val->quantity_to_ship) ? 0 : $val->quantity_to_ship;
				
				$shippedqty = $val->quantity_shipped + $row['quantity'];
				$qtytoship = $val->quantity_to_ship - $row['quantity'];
				$qtytobeship = $val->quantity_to_be_shipped - $row['quantity'];
				$qtytobeship = empty($qtytobeship) ? 0 : $qtytobeship;
				
				//update shipqty into the quantity_to_be_shipped column and price
				DB::table('customer_orders')
				->where('order_id', $val->order_id)
				->where('order_item_id', $val->order_item_id)
				->where('sku', $val->sku)
				->update([
					'quantity_to_be_shipped' => $qtytobeship,
					'quantity_to_ship' => $qtytoship,
					'quantity_shipped' => $shippedqty,
					'price' => $row['price'],
					'shipping_price' => $row['shipping_price'],
				]);
			}
		}

		// insert and update product image path in product image table
        return new OrderTrack([
            'price' => $row['price'],
            'selling_price' => $row['selling_price'],
            'shipping_price' => $row['shipping_price'],
            'order_id' => $row['order_id'],
			'order_item_id' => $row['order_item_id'],
			'sku' => $row['sku'],
			'isbnno' => $row['isbn13'],
			'warehouse_id' => $row['warehouse_id'],
			'warehouse_name' => $row['warehouse'],
			'box_shipper_id' => $row['box_shipper_id'],
			'shipper_tracking_id' => $row['shipper_tracking_id'],
			'box_id' => $row['box_id'],
			'shipper_id' => $row['shipper_id'],
			'shipment_date' => $row['shipment_date'],
			'quantity_shipped' => $row['quantity'],
			'ncp' => $row['ncp'],
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
