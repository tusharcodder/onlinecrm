<?php

namespace App\Imports;

use App\VendorStock;
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
class VendorStockImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
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
		
		if($importtype == "newimport" && $this->rows == 1){ // for new import
			VendorStock::where('vendor_id', strval($row['vendor_id']))
                ->update(['quantity' => 0]);	
		}elseif($importtype == "importwithupdate"){// import with update
			//
		}
		
		// check duplicate stock exists or not
		$stkdata = VendorStock::where('vendor_id', '=', strval($row['vendor_id']))
			->where('isbnno', '=', strval($row['isbnno']))
			->get();
		if(!empty(count($stkdata))){
			VendorStock::where('vendor_id', strval($row['vendor_id']))
			->where('isbnno', '=', strval($row['isbnno']))
			->update([
				'vendor_id' => strval($row['vendor_id']),
				'isbnno' => strval($row['isbnno']),
				'name' => strval($row['name']),
				'stock_date' => Carbon::parse(strval($row['stock_date']))->format('Y-m-d'),
				'author' => strval($row['author']),
				'publisher' => strval($row['publisher']),
				'binding_id' => strval($row['binding_id']),
				'currency_id' => strval($row['currency_id']),
				'price' => strval($row['price']),
				'discount' => strval($row['discount']),
				'quantity' => strval($row['quantity']),
				'updated_by' => $uid,
			]);				
			return null;
		}
		
		// insert and update product image path in product image table
        return new VendorStock([
            'vendor_id' => strval($row['vendor_id']),
            'isbnno' => strval($row['isbnno']),
            'name' => strval($row['name']),
            'stock_date' => Carbon::parse(strval($row['stock_date']))->format('Y-m-d'),
            'author' => strval($row['author']),
            'publisher' => strval($row['publisher']),
            'binding_id' => strval($row['binding_id']),
            'currency_id' => strval($row['currency_id']),
            'price' => strval($row['price']),
            'discount' => strval($row['discount']),
            'quantity' => strval($row['quantity']),
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
