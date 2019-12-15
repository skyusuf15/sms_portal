<?php

namespace App\Http\Controllers\Admin;

use App\SmsHistory;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use App\Http\Controllers\Controller;

class MessageHistoryController extends Controller
{
    protected $request;

    function __construct (Request $request) {
        $this->request = $request;
    }

    public function upload () {
        return view('dashboard.sms_history.index');
    }

    public function upload_file () {
        $this->validate($this->request, [
            'file' => 'file|required'
        ]);
        
        $path_file = '';
        $file_extension = $this->request->file('file')->getClientOriginalExtension();
        $random = str_random(40);
        if ($this->request->file('file')->isValid()) {
           $path_file = $this->request->file('file')->storeAs('file', $random.'_'. Carbon::now()->toDateString() .'.'.$file_extension);
        }

        $count = $this->_import_csv(storage_path('app/'.$path_file));
        $this->request->session()->flash('alert', [ 'title' => 'Success', 'message' =>$count. ' Rows inserted! Report Uploaded successfully!', 'status' => 'success']);
        return back();
    }
 
    private function _import_csv($csv_path)
    {
        $query = "LOAD DATA LOCAL INFILE '". addslashes($csv_path). "' INTO TABLE sms_histories FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '|' ENCLOSED BY '\"' LINES TERMINATED BY '\n' IGNORE 1 LINES (BatchID, ReportDate, MobileNumber, Status, UnitsCharged, @created_at, @updated_at) SET created_at=NOW(), updated_at=NOW()";
        return DB::connection()->getpdo()->exec($query);
    }

    public function show () {
        $sms_history = SmsHistory::paginate(20);
        return view('dashboard.sms_history.show', compact('sms_history'));
    }
}
