<?php

namespace App\Imports;

use App\PurchaseOrder;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Illuminate\Support\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class PurchaseOrderImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
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
        $afterdiscountamt = ((float)($row['mrp'])-((float)($row['mrp'])*(float)($row['discount']))/100)*(int)$row['quantity'];
        return new PurchaseOrder([
            'bill_no'=>$row['bill_no'],
            'isbn13'=>strval($row['isbn13']),           //'book_title'=>$row['book_title'], 
            'vendor_id'=>$row['vendor'],
            'quantity'=>$row['quantity'],
            'mrp'=>$row['mrp'],
            'discount'=>$row['discount'],
            'cost_price'=>$afterdiscountamt,
            'purchase_by'=>$row['purchase_by'],
            'purchase_date'=> Carbon::parse($row['purchase_date'])->format('Y-m-d'),
            'create_by'=>$uid,
            'update_by'=>$uid,
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
