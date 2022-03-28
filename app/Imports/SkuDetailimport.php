<?php

namespace App\Imports;

use App\SkuDetail;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Illuminate\Support\Carbon;

HeadingRowFormatter::default('none');
class SkuDetailimport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
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
		
		if(!empty(count($skudata))){ // remove same duplicate sku value
			DB::table('skudetails')
				->where('sku_code',strval($row['sku_code']))
				->delete();
		}
		$oz_wt = round(((float)$row['weight(kg)'] * 35.2739),2);//calc ounces wgt
        
        return new SkuDetail([
            'market_id' => strval($row['Market_Place']),
            //'warehouse_id' =>$row['Warehouse'],
            'isbn13' =>strval($row['isbn13']),
            'isbn10' =>strval($row['isbn10']),
            'sku_code' =>strval($row['sku_code']),
            'mrp' =>strval($row['mrp']),           
            'disc' =>strval($row['disc']),
            'wght' =>strval($row['weight(kg)']),
            'type' =>strval($row['type']),
            'oz_wt' => $oz_wt,
            'created_by' =>$uid,
            'updated_by'=>$uid,
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
