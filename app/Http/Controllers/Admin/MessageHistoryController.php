<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use App\Http\Controllers\Controller;

class MessageHistoryController extends Controller
{
    public function upload () {
        return view('dashboard.sms_history.index');
    }

    public function upload_file (Request $request) {
        $this->validate($request, [
            'file' => 'file|required'
        ]);
        
        $path_file = '';
        $file_extension = $request->file('file')->getClientOriginalExtension();
        $random = str_random(40);
        if ($request->file('file')->isValid()) {
           $path_file = $request->file('file')->storeAs('file', $random.'_'. Carbon::now()->toDateString() .'.'.$file_extension);
        }

        $count = $this->_import_csv(storage_path('app/'.$path_file));
        return back()->withStatus($count. ' Rows inserted! Report Uploaded successfully!');
    }

    private function _import_csv($csv_path)
    {
        $query = /*sprintf(*/ "LOAD DATA LOCAL INFILE '". addslashes($csv_path). "' INTO TABLE sms_histories FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '|' ENCLOSED BY '\"' LINES TERMINATED BY '\n' IGNORE 1 LINES (BatchID, ReportDate, MobileNumber, Status, UnitsCharged, @created_at, @updated_at) SET created_at=NOW(), updated_at=NOW()";//, addslashes($csv_path));
        return DB::connection()->getpdo()->exec($query);
    }
}
