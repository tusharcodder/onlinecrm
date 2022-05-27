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

HeadingRowFormatter::default('none');
class CustomerOrderImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
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
		if(!empty(count($customerdata))) // not inserted duplicated data
			return null;
		
		// insert and update product image path in product image table
        return new CustomerOrder([
            'order_id' => strval($row['order-id']),
			'order_item_id' => strval($row['order-item-id']),
			'purchase_date' => strval($row['purchase-date']),
			'payments_date' => strval($row['payments-date']),
			'reporting_date' => strval($row['reporting-date']),
			'promise_date' => strval($row['promise-date']),
			'days_past_promise' => strval($row['days-past-promise']),
			'buyer_email' => strval($row['buyer-email']),
			'buyer_name' => strval($row['buyer-name']),
			'buyer_phone_number' => strval($row['buyer-phone-number']),
			'sku' => strval($row['sku']),
			'product_name' => strval($row['product-name']),
			'quantity_purchased' => strval($row['quantity-purchased']),
			'quantity_shipped' => strval($row['quantity-shipped']),
			'quantity_to_ship' => strval($row['quantity-to-ship']),
			'ship_service_level' => strval($row['ship-service-level']),
			'recipient_name' => strval($row['recipient-name']),
			'ship_address_1' => strval($row['ship-address-1']),
			'ship_address_2' => strval($row['ship-address-2']),
			'ship_address_3' => strval($row['ship-address-3']),
			'ship_city' => strval($row['ship-city']),
			'ship_state' => strval($row['ship-state']),
			'ship_postal_code' => strval($row['ship-postal-code']),
			'ship_country' => strval($row['ship-country']),
			'is_business_order' => strval($row['is-business-order']),
			'purchase_order_number' => strval($row['purchase-order-number']),
			'price_designation' => strval($row['price-designation']),
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
