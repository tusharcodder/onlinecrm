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

        return new SkuDetail([
            'market_id' => $row['Market_Place'],
            //'warehouse_id' =>$row['Warehouse'],
            'isbn13' =>$row['isbn13'],
            'isbn10' =>$row['isbn10'],
            'sku_code' =>$row['sku_code'],
            'mrp' =>$row['mrp'],           
            'disc' =>$row['disc'],
            'wght' =>$row['weight(kg)'],
            'pkg_wght' =>$row['pkg_weight(kg)'],
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
