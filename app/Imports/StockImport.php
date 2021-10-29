<?php

namespace App\Imports;

use App\Stock;
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
class StockImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
{
	
	use Importable;
	private $rows = 0;
	
	// request constructor
	public function __construct($request, $path, $filelist)
    {
		// get form request value
		$this->request = $request;
		$this->path = $path;
		$this->filelist = $filelist;
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
		
		$storefilepath = '';
		if(!empty($row['product_image'])){
			$storefilepath = $this->path.'/'.$row['product_image'];
		}
		
		// call common method for adding product image
		$common = new Common();
		$common -> addProductImage($row['product_code'], $storefilepath);
		
		// insert and update product image path in product image table
        return new Stock([
            'manufacturer_name' => $row['manufacturer_name'],
            'country' => $row['country'],
            'manufacture_date' => Carbon::parse($row['manufacture_date'])->format('Y-m-d'),
            'cost' => $row['cost'],
            'stock_date' => Carbon::parse($row['stock_date'])->format('Y-m-d'),
            'brand' => $row['brand'],
            'category' => $row['category'],
            'gender' => $row['gender'],
            'colour' => $row['colour'],
            'size' => $row['size'],
            'lotno' => $row['lotno'],
            'sku_code' => $row['sku_code'],
            'product_code' => $row['product_code'],
            'hsn_code' => $row['hsn_code'],
            'online_mrp' => $row['online_mrp'],
            'offline_mrp' => $row['offline_mrp'],
            'quantity' => $row['quantity'],
            'description' => $row['description'],
            'image_url' => $storefilepath,
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
