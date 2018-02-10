<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Excel;
use Illuminate\Support\Facades\Log;
use Khill\Lavacharts\Lavacharts;
use Validator;

use Lava;

class FileController extends Controller
{
	public function store(Request $request) {

		//return $request->file;
		//
		/*$extension = $request->file->getClientOriginalExtension();
		$filename = 'sample'.$extension;
		$request->file->move(public_path(), $filename);


		return mime_content_type(public_path().'/sample.xlsx');*/
		
		// return $request->file->getMimeType();

		//======================= UPLOAD THE FILE =============================
  		$v = Validator::make($request->all(), ['file' => 'required'], ['file.required' => 'Please upload a valid file']);
		

  		if ($v->fails()) {
	    	//return $v->errors();
	        return redirect()->back()->withErrors($v->errors());
	    }


	    $inputs = [
	    	'file'	=> $request->all(),
	      	'mime_types' => strtolower($request->file->getMimeType()),
	  	];
  		
  		$rules = [
      		'file'          => 'required',
      		'mime_types'    => 'required|in:application/csv,application/excel,application/vnd.ms-excel,application/vnd.msexcel,text/csv,text/anytext,text/plain,text/x-c,text/comma-separated-values,inode/x-empty, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/octet-stream',
  		];

  		$messages = [
			'file.required'	=> 'You did NOT upload a file.',
			'mime_types.in' => 'File should be a csv, xls or xlsx.'
		];

		$v = Validator::make($inputs, $rules, $messages);


	    if ($v->fails()) {
	    	//return $v->errors();
	        return redirect()->back()->withErrors($v->errors());
	    }


	    $extension = $request->file->getClientOriginalExtension();
		$filename = 'sample'.$extension;
		$request->file->move(public_path(), $filename);

		//============READ THE FILE AND CONVERT THE FILE TO AN ARRAY==========
    	$results = Excel::load('sample.xlsx', function($reader) {

		})->toArray();


    	//======================= PLOT THE GRAPH ==============================
    	$array_keys = array_keys($results[0]);
    	$counter = 0;

    	for ($i=0; $i<sizeof($array_keys);$i++) {
    		
    		$arrcopy = $array_keys;

    		// removes and returns the i'th element
    		$elem = array_splice($arrcopy, $i, 1); 

    		foreach ($arrcopy as $key => $value) {

    			if($value === 0 || $elem[0]  === 0){
    				break;
    			}

		    	$column1 = $elem[0];
		    	$column2 = $value;
				$datatable = Lava::DataTable();
				$datatable->addNumberColumn($column1);
				$datatable->addNumberColumn($column2);

				foreach ($results as $key => $value) {
					
					$value_column1 = !empty(trim($value[$column1]))?$value[$column1]:0;
					$value_column2 = !empty(trim($value[$column2]))?$value[$column2]:0;

					$datatable->addRow([$value_column1, $value_column2]);
				}

				$counter++;
				$graph = "graph".$counter;

				Lava::ScatterChart($graph, $datatable, [
				    'width' => 400,
				    'legend' => [
				        'position' => 'none'
				    ],
				    'hAxis' => [
				        'title' => $column1
				    ],
				    'vAxis' => [
				        'title' => $column2
				    ]
				]);

    		}
		}
		return view('graph', ['counter' => $counter]);; 
		

	}


}
