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
		
		$customerdata = CustomerOrder::where('order_id', '=', $row['order-id'])->get();
		if(!empty(count($customerdata))) // not inserted duplicated data
			return '';
		
		// insert and update product image path in product image table
        return new CustomerOrder([
            'order_id' => $row['order-id'],
			'order_item_id' => $row['order-item-id'],
			'purchase_date' => $row['purchase-date'],
			'payments_date' => $row['payments-date'],
			'reporting_date' => $row['reporting-date'],
			'promise_date' => $row['promise-date'],
			'days_past_promise' => $row['days-past-promise'],
			'buyer_email' => $row['buyer-email'],
			'buyer_name' => $row['buyer-name'],
			'buyer_phone_number' => $row['buyer-phone-number'],
			'sku' => $row['sku'],
			'product_name' => $row['product-name'],
			'quantity_purchased' => $row['quantity-purchased'],
			'quantity_shipped' => $row['quantity-shipped'],
			'quantity_to_ship' => $row['quantity-to-ship'],
			'ship_service_level' => $row['ship-service-level'],
			'recipient_name' => $row['recipient-name'],
			'ship_address_1' => $row['ship-address-1'],
			'ship_address_2' => $row['ship-address-2'],
			'ship_address_3' => $row['ship-address-3'],
			'ship_city' => $row['ship-city'],
			'ship_state' => $row['ship-state'],
			'ship_postal_code' => $row['ship-postal-code'],
			'ship_country' => $row['ship-country'],
			'is_business_order' => $row['is-business-order'],
			'purchase_order_number' => $row['purchase-order-number'],
			'price_designation' => $row['price-designation'],
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
