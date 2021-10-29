<?php

namespace App\Http\Controllers;

use File;
use Session;
use App\CommissionReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use DateTime;
use App\Rules\DateRange; // date range rule validation
use DB;
use App\Exports\CommissionReportExport;
use App\Exports\AggregatorvencommissionreportExport;
use App\Exports\NotbasedReportExport;
use Maatwebsite\Excel\Facades\Excel;

class CommissionreportController extends Controller
{
	
	
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
		$this->middleware('permission:commission-report-list', ['only' => ['index', 'search']]);
		$this->middleware('permission:commission-report-download', ['only' => ['downloadcommissionreport']]);
    }
	
	 /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
		$type = '';
		$vendor = '';
		$aggregator_vendor = '';
		$skucode = '';
		$brand = '';
		$category = '';
		$gender = '';
		$colour = '';
		$product_code = '';
		$from_date = '';
		$to_date = '';
		$ctype = '';
		$beforetodaydate = date('Y-m-d', strtotime(date('Y-m-d') . ' -7 day'));
		$current_date =date('Y-m-d');
		$vtype = ['Aggregator', 'Online', 'SOR', 'Outride'];		
		$datarange = DB::table('gstslabs as GST')->select('GST.*')->get();			
		$commissionreports = DB::table('sales')
			->select('sales.sale_date','sales.vendor_type as Type','sales.vendor_name as Venname','sales.aggregator_vendor_name as avname','sales.Brand','sales.category', 'sales.Product_code as pcode','sales.sku_code as SkuCode',DB::raw('sum(sales.total_sale_amount) as saleamt'),'v.commission',DB::raw('CAST( (sum(sales.total_sale_amount))*v.commission/100 AS DECIMAL(10,2)) as commvalue'),
			DB::raw('sum(case when igst is null then (cgst+sgst) else igst end) as gst'))
			->join('vendors as v',function($q){
				$q->on('sales.vendor_type','=','v.type')
				->on('sales.vendor_name','=','v.vendor_name');
			})
			->where('sales.sale_date','<=',$current_date)
			->where('sales.sale_date','>',$beforetodaydate) 	
			->where('sales.vendor_type','!=','Aggregator')
			->groupBy('sales.sale_date','sales.Product_code','sales.sku_code')	
			//->orderBy('s.sale_date','DESC')
			->paginate(10)
			->setPath('');
		
        return view('reports.commissionreport',compact('vtype', 'commissionreports', 'request', 'type', 'vendor', 'aggregator_vendor', 'skucode', 'brand', 'category','product_code', 'from_date', 'to_date','ctype'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
    }
	
	 /**
     * Display the specified resource.
     *
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
         //
		$type = $request->input('type');
		$vendor = $request->input('vendor');
		$aggregator_vendor = $request->input('aggregator_vendor');
		$skucode = $request->input('skucode');
		$brand = $request->input('brand');
		$category = $request->input('category');		
		$product_code = $request->input('product_code');
		$from_date = $request->input('from_date');
		$to_date = $request->input('to_date');
		$ctype = $request->input('ctype');
		
		$vtype = ['Aggregator', 'Online', 'SOR', 'Outride'];
		$commissionreports = null;	
		
		if($ctype=='Commission Based' && $type !='Aggregator'){				
			$commissionreports = DB::table('sales as s')
			->select('s.sale_date','s.vendor_type as Type','s.vendor_name as Venname','s.Brand','s.category', 's.Product_code as pcode','s.sku_code as SkuCode',DB::raw('sum(s.total_sale_amount) as saleamt'),'vendors.commission',DB::raw('CAST( (sum(s.total_sale_amount))*vendors.commission/100 AS DECIMAL(10,2)) as commvalue'),
			DB::raw('sum(case when igst is null then (cgst+sgst) else igst end) as gst'))->join("vendors","vendors.vendor_name","=","s.vendor_name","Inner");
			if(!empty($type))
				$commissionreports = $commissionreports->where('s.vendor_type',$type);
			if(!empty($vendor))
				$commissionreports = $commissionreports->where('s.vendor_name',$vendor);				
			 if(!empty($skucode))
				$commissionreports = $commissionreports->where('s.sku_code',$skucode);
			if(!empty($brand))
				$commissionreports = $commissionreports->where('s.brand',$brand);
			if(!empty($category))
				$commissionreports = $commissionreports->where('s.category',$category);
			
			if(!empty($product_code))
				$commissionreports = $commissionreports->where('s.product_code',$product_code);
			if(!empty($from_date))
				$commissionreports = $commissionreports->where('s.sale_date', '>=' ,$from_date);
			if(!empty($to_date))
				$commissionreports = $commissionreports->where('s.sale_date', '<=' ,$to_date);
			$commissionreports = $commissionreports->where('s.vendor_type','!=','Aggregator');		
			$commissionreports = $commissionreports->groupBy('s.sku_code','s.product_code','s.sale_date');
			$commissionreports = $commissionreports->paginate(10);
			$commissionreports = $commissionreports->setPath('');		
			
			// bind value with pagination link
			$pagination = $commissionreports->appends ( array (
				'type' => $type,
				'vendor' => $vendor,
				'aggregator_vendor' => $aggregator_vendor,
				'skucode' => $skucode,
				'brand' => $brand,
				'category' => $category,				
				'product_code' => $product_code,
				'from_date' => $from_date,
				'to_date' => $to_date,
				'ctype' => $ctype,
			));
			
			
			/* print_r($commissionreports);
			exit; */
			return view('reports.commissionreport',compact('vtype','commissionreports', 'request', 'type', 'vendor', 'aggregator_vendor', 'skucode', 'brand', 'category', 'product_code', 'from_date', 'to_date','ctype'))
			->with('i', ($request->input('page', 1) - 1) * 10);
			
			
		}else if($ctype=='Commission Based' && $type =='Aggregator'){
			
			$commissionreports = DB::table('sales as s')
				->select('s.sale_date','s.vendor_type as Type','s.vendor_name as Venname','s.aggregator_vendor_name as avname','s.Brand','s.category', 's.Product_code as pcode','s.sku_code as SkuCode','agg.aggregator_vendor_commission as aggvencomm',DB::raw('sum(s.total_sale_amount) as saleamt'),'vendors.commission',DB::raw('CAST( (sum(s.total_sale_amount))*vendors.commission/100 AS DECIMAL(10,2)) as commvalue'),
				DB::raw('sum(case when igst is null then (cgst+sgst) else igst end) as gst'))->join("vendors","vendors.vendor_name","=","s.vendor_name","Inner");
				$commissionreports = $commissionreports->join("aggregator_has_vendors as agg","agg.vendor_id","=","vendors.id","Inner");
				if(!empty($type))
					$commissionreports = $commissionreports->where('s.vendor_type',$type);
				if(!empty($vendor))
					$commissionreports = $commissionreports->where('s.vendor_name',$vendor);
				if(!empty($aggregator_vendor))
					$commissionreports = $commissionreports->where('s.aggregator_vendor_name',$aggregator_vendor);
				 if(!empty($skucode))
					$commissionreports = $commissionreports->where('s.sku_code',$skucode);
				if(!empty($brand))
					$commissionreports = $commissionreports->where('s.brand',$brand);
				if(!empty($category))
					$commissionreports = $commissionreports->where('s.category',$category);
				
				if(!empty($product_code))
					$commissionreports = $commissionreports->where('s.product_code',$product_code);
				if(!empty($from_date))
					$commissionreports = $commissionreports->where('s.sale_date', '>=' ,$from_date);
				if(!empty($to_date))
					$commissionreports = $commissionreports->where('s.sale_date', '<=' ,$to_date);				
				$commissionreports = $commissionreports->groupBy('s.sku_code','s.product_code','s.sale_date');
				$commissionreports = $commissionreports->paginate(10);
				$commissionreports = $commissionreports->setPath('');
				
				// bind value with pagination link
				$pagination = $commissionreports->appends ( array (
					'type' => $type,
					'vendor' => $vendor,
					'aggregator_vendor' => $aggregator_vendor,
					'skucode' => $skucode,
					'brand' => $brand,
					'category' => $category,				
					'product_code' => $product_code,
					'from_date' => $from_date,
					'to_date' => $to_date,
					'ctype' => $ctype,
			));
			
			return view('reports.commissionreport',compact('vtype','commissionreports', 'request', 'type', 'vendor', 'aggregator_vendor', 'skucode', 'brand', 'category', 'product_code', 'from_date', 'to_date','ctype'))
			->with('i', ($request->input('page', 1) - 1) * 10);
			
		}
		else if($ctype=='NOT Based' && $type !='Aggregator'){
			$commissionreports = DB::table('sales as s')
				->select('s.sale_date','s.vendor_type as Type','s.vendor_name as Venname','s.aggregator_vendor_name as avname','s.Brand','s.category', 's.Product_code as pcode','s.sku_code as SkuCode','mrp',DB::raw('sum(s.quantity) as qty'),
				DB::raw('sum(s.quantity)*mrp as mrpvalue'),
				's.vendor_discount',
				'vendors.commission',DB::raw('CAST( (s.mrp)*vendors.commission/100 AS DECIMAL(10,2)) as commvalue'),
				DB::raw('sum(case when igst is null then (cgst+sgst) else igst end) as gst'))->join("vendors","vendors.vendor_name","=","s.vendor_name","Inner");				
				if(!empty($type))
					$commissionreports = $commissionreports->where('s.vendor_type',$type);
				if(!empty($vendor))
					$commissionreports = $commissionreports->where('s.vendor_name',$vendor);
				
				 if(!empty($skucode))
					$commissionreports = $commissionreports->where('s.sku_code',$skucode);
				if(!empty($brand))
					$commissionreports = $commissionreports->where('s.brand',$brand);
				if(!empty($category))
					$commissionreports = $commissionreports->where('s.category',$category);
				
				if(!empty($product_code))
					$commissionreports = $commissionreports->where('s.product_code',$product_code);
				if(!empty($from_date))
					$commissionreports = $commissionreports->where('s.sale_date', '>=' ,$from_date);
				if(!empty($to_date))
					$commissionreports = $commissionreports->where('s.sale_date', '<=' ,$to_date);
				
				$commissionreports = $commissionreports->where('s.vendor_type','!=','Aggregator');			
				$commissionreports = $commissionreports->groupBy('s.sku_code','s.product_code','s.sale_date');
				$commissionreports = $commissionreports->paginate(10);
				$commissionreports = $commissionreports->setPath('');
				
				// bind value with pagination link
				$pagination = $commissionreports->appends ( array (
					'type' => $type,
					'vendor' => $vendor,
					'aggregator_vendor' => $aggregator_vendor,
					'skucode' => $skucode,
					'brand' => $brand,
					'category' => $category,				
					'product_code' => $product_code,
					'from_date' => $from_date,
					'to_date' => $to_date,
					'ctype' => $ctype,
			));
			return view('reports.commissionreport',compact('vtype','commissionreports', 'request', 'type', 'vendor', 'aggregator_vendor', 'skucode', 'brand', 'category', 'product_code', 'from_date', 'to_date','ctype'))
			->with('i', ($request->input('page', 1) - 1) * 10);
		}
    }
	//get gst percantage
	public static function getGstFromPriceRange($val){
		$datarange = DB::table('gstslabs as GST')->select('GST.*')->get();
		foreach($datarange as $rangeval){
			if($val>=$rangeval->amount_from && $val<=$rangeval->amount_to)
			{
				return $rangeval->gst_per;
				exit;
			}
		}
		return 0;
	}

	 /**
    * @return \Illuminate\Support\Collection
	* export commisson report except aggregator vendor
    */
    public function export(Request $request) 
    {	$ctype = $request->input('type');
		if($ctype !='Aggregator'){
			return Excel::download(new CommissionReportExport($request), "commissionreport.".$request['exporttype']);
		}		
    }
	
	 /**
    * @return \Illuminate\Support\Collection
	* export aggregator vendor commission report
    */
    public function export2(Request $request) 
    {	
       return Excel::download(new AggregatorvencommissionreportExport($request), "Aggregatorvendorcommissionreport.".$request['exportfile2']);
    }
	 /**
    * @return \Illuminate\Support\Collection
	* export NOT based report
    */
    public function export3(Request $request) 
    {	
       return Excel::download(new NotbasedReportExport($request), "NotbasedReport.".$request['exportfile3']);
    }
}