<?php

namespace App\Imports;

use App\Sale;
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

HeadingRowFormatter::default('none');
class SaleImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
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
        return new Sale([
            'sale_date' => Carbon::parse($row['sale_date'])->format('Y-m-d'),
            'invoice_no' => $row['invoice_no'],
            'po_no' => $row['po_no'],
            'brand' => $row['brand'],
            'category' => $row['category'],
            'vendor_type' => $row['vendor_type'],
            'vendor_name' => $row['vendor_name'],
            'aggregator_vendor_name' => $row['aggregator_vendor_name'],
            'hsn_code' => $row['hsn_code'],
            'sku_code' => $row['sku_code'],
            'product_code' => $row['product_code'],
            'colour' => $row['colour'],
            'size' => $row['size'],
            'quantity' => $row['quantity'],
			'vendor_discount' =>$row['vendor_discount'],
            'mrp' => $row['mrp'],
            'before_tax_amount' => $row['before_tax_amount'],
            'state' => $row['state'],
            'cgst' => $row['cgst'],
            'sgst' => $row['sgst'],
            'igst' => $row['igst'],
            'sale_price' => $row['sale_price'],
            'total_sale_amount' => $row['total_sale_amount'],
            'cost_price' => $row['cost_price'],
            'total_cost_amount' => $row['total_cost_amount'],
            'receivable_amount' => $row['receivable_amount'],
            'created_by' => $uid,
            'updated_by' => $uid,
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