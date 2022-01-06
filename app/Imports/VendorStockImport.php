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
		
        //vendor stock delete before current date
        $current_date=Carbon::parse($row['stock_date'])->format('Y-m-d');
        DB::table('vendor_stocks')
            ->where('vendor_id',$row['vendor_id'])
            ->where('stock_date','<',$current_date)
            ->delete();
		
		// insert and update product image path in product image table
        return new VendorStock([
            'vendor_id' => $row['vendor_id'],
            'isbnno' => $row['isbnno'],
            'name' => $row['name'],
            'stock_date' => Carbon::parse($row['stock_date'])->format('Y-m-d'),
            'author' => $row['author'],
            'publisher' => $row['publisher'],
            'binding_id' => $row['binding_id'],
            'currency_id' => $row['currency_id'],
            'price' => $row['price'],
            'discount' => $row['discount'],
            'quantity' => $row['quantity'],
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
