<?php

namespace App\Imports;

use App\UspsPrice;
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
class UspsPriceImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
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
        
		// insert into table
        if(!empty(strval($row['tracking_no']))){
            return new UspsPrice([
                'tracking_no' => strval($row['tracking_no']),
                'volume_wgt' => strval($row['volume_wgt']),
                'price' => strval($row['price']),               		
            ]);
        }
       
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
