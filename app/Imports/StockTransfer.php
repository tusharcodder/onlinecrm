<?php

namespace App\Imports;
use App\WarehouseStock;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use DB;

HeadingRowFormatter::default('none');
class StockTransfer implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
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
        $notsaveisbns = [];
        $importtype = $this->request['importtype'];
		++$this->rows;
		
		// current login id
		$user = Auth::user();
		$uid = $user->id;

        $row['quantity'] = empty($row['quantity']) ? 0 : $row['quantity'];
        //check the quantity 
        $stockqty = DB::table('warehouse_stocks')->select('quantity')
                    ->where('warehouse_id', strval($row['warehouse_from']))
                    ->where('isbn13', strval($row['isbn13']))->get();

        if($row['quantity'] <=$stockqty[0]->quantity){

            $updatedqty = ($stockqty[0]->quantity- $row['quantity']);

            //update quantity into TJW stock
            DB::table('warehouse_stocks')
            ->where('warehouse_id', strval($row['warehouse_from']))
            ->where('isbn13', strval($row['isbn13']))
            ->update(array('quantity'=>$updatedqty));
            
            $isExist = DB::table('warehouse_stocks')
                        ->select('*')->where('warehouse_id', strval($row['warehouse_to']))
                        ->where('isbn13',strval($row['isbn13']))->get();

            //update when isbn already in warehouse to            
            if($isExist->count() > 0){
                $updatenewqty = ($isExist[0]->quantity + $row['quantity']);
                DB::table('warehouse_stocks')
                ->where('warehouse_id', strval($row['warehouse_to']))
                ->where('isbn13', strval($row['isbn13']))
                ->update(array('quantity'=> $updatenewqty));           
            }else{
                return new WarehouseStock([
                    'warehouse_id' => strval($row['warehouse_to']),           
                    'isbn13' =>strval($row['isbn13']),           
                    'quantity' =>strval($row['quantity'])
                ]);
            }
           
        }else{
            $notsaveisbns=array("isbn"=>strval($row['isbn13']));
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
