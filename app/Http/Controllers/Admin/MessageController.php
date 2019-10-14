<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use \DB;

use Carbon\Carbon;

use Excel;
use GuzzleHttp\Client;


use App\SMS;
use App\SMSBatch;

class MessageController extends Controller
{
    //

    protected $request;
    protected $sessionId = null;
    protected $client;

    function __construct (Request $request) {
        $this->request = $request;
        $this->client = new Client([
            'base_uri'=> 'http://www.smslive247.com/http/index.aspx'
        ]);
    }

    public function index(){
        $smsBatches = SMSBatch::with('messagesCount')->paginate(15);
        // return $smsBatches;
        return view('dashboard.sms.index', compact('smsBatches'));
    }

    public function showSMSBatch($batch_no) {
        $smsBatch = SMSBatch::where('batch_no', $batch_no)->first();
        $messages = SMS::where('batch_id', $smsBatch->id)->paginate(15);
        return view('dashboard.sms.smsBatch',compact('smsBatch', 'messages'));
    }

    public function showSMS($message_id) {
        $message = SMS::where('messageId', $message_id)->with('batch')->first();
        // return $message;
        return view('dashboard.sms.message',compact('message'));
    }

    public function showSendBulk(){
        return view('dashboard.sms.send');
    } 

    public function showSendQuick(){
        return view('dashboard.sms.sendQuick');
    }

    public function sendQuick(){
        $this->request->validate([
            'receipient' => 'required',
            'message' => 'required'
        ]);

        //$sender = 'DND_BYPASS'.$this->request->input('sender', env('APP_NAME'));
        $sender = $this->request->input('sender', env('APP_NAME'));

        $receipient =  "234".ltrim($this->request->receipient, '0');
        $message = $this->request->message;

        $smsBatch = SMSBatch::create([
            'user_id' => Auth::id(),
            'batch_no' => uniqid(time()),
            'status' => 0,
        ]);

        $messageId = $this->sendSMS($sender, $receipient, $message);

        // dd($messageId);

        SMS::create([
            'user_id' => Auth::id(),
            'batch_id' => $smsBatch->id,
            'messageId' => $messageId,
            'sender' => $sender,
            'receipient' => $receipient,
            'message' => $message,
            'status' => 1,
            'created_at' => $smsBatch->created_at,
            'updated_at' => $smsBatch->updated_at
        ]);

        $smsBatch->update(['status' => 1]);

        $this->request->session()->flash('alert', [ 'title' => 'Success', 'message' =>'SMS Sent Successfully', 'status' => 'success']);

        return back();
    }

    public function sendBulk(){

        $this->request->validate([
            'contacts' => 'file|required',
            'message' => 'required'
        ]);

        // $sender = 'DND_BYPASS'.$this->request->input('sender', env('APP_NAME'));
        $sender = $this->request->input('sender', env('APP_NAME'));

        $extension = $this->request->contacts->extension();

        if($extension == "xlsx" || $extension == "xls" ){
            $path = $this->request->contacts->storeAs('uploads', "contacts.$extension");
            $path = "storage/app/$path";

            // dd($path);

            $contacts = Excel::load($path)->get(); // get all rows
            // return $contacts;
            $contacts = collect($contacts)->filter(function($contact){ // remove empty rows
                return collect($contact)->isNotEmpty();
            });

            if($this->array_depth($contacts) > 1){
                $contacts = array_flatten($contacts, 1); // flatten the array to first depth
            }
            // return $contacts;
            $contacts = collect($contacts)->filter(function($contact){ // remove contacts with no phone number
                return !is_null($contact->phone);
            });
            // return $contacts;
            $symbols = $this->findSymbols('#', $this->request->message);

            $messages = collect($contacts)->map(function($contact) use($symbols) {
                $message = $this->request->message;
                if($symbols){
                    foreach ($symbols as $symbol) {
                        $message = str_replace($symbol, $contact->{ltrim($symbol,'#')}, $message);
                    }
                }
                return ['phone' =>  "234".ltrim($contact->phone, '0'), 'message' => $message];
            });
            // return $messages;

            $smsBatch = SMSBatch::create([
                'user_id' => Auth::id(),
                'batch_no' => uniqid(time()),
                'status' => 0,
            ]);
            $smsResponses = [];
            foreach ($messages as $message ) {
                $messageId = $this->sendSMS($sender, $message['phone'], $message['message']);
                $smsResponses = [
                    'user_id' => Auth::id(),
                    'batch_id' => $smsBatch->id,
                    'messageId' => $messageId,
                    'sender' => $sender,
                    'receipient' => $message['phone'],
                    'message' => $message['message'],
                    'status' => 1,
                    'created_at' => $smsBatch->created_at,
                    'updated_at' => $smsBatch->updated_at
                ];
                SMS::insert($smsResponses);
            }

            //SMS::insert($smsResponses);
            $smsBatch->update(['status' => 1]);

            // dd($this->sendSMS());
            $this->request->session()->flash('alert', [ 'title' => 'Success', 'message' =>'SMS Sent Successfully', 'status' => 'success']);
            return back();

        }else {
            return back()->withInput()->withErrors(['contacts' => 'Please upload an excel file']);
        }

    }

    private function findSymbols($symbolIndicator, $string){
        $regex = '~('.$symbolIndicator.'\w+)~';
        if (preg_match_all($regex, $string, $matches, PREG_PATTERN_ORDER)) {
            return $matches[0];
        }
    }

    private function getSessionId(){
        if(is_null($this->sessionId)) {
            $authResponse = $this->client->request('GET', '', [
                'query' => [
                    'cmd' => 'login',
                    'OwnerEmail' => env('SMS_LIVE_EMAIL','damilola.dammie@gmail.com'),
                    'SubAcct' => env('SMS_LIVE_SUB_ACCOUNT','Ibiton_Test'),
                    'SubAcctPwd' => env('SMS_LIVE_PASSWORD','Pasme@123!')
                ]
            ]);
            // dd($authResponse);
            if($authResponse->getReasonPhrase() == 'OK'){
                $content = $authResponse->getBody()->getContents();
                return ltrim($content, 'OK: ');
            }else{
                throw new Error('Error While Getting Session ID');
            }
        }else{
            return $this->sessionId;
        }
    }

    private function sendSMS($sender, $receipient, $message, $cmd = 'sendquickmsg'){
        $this->sessionId = $this->getSessionId();
        $sendSMSResponse =  $this->client->request('GET', '', [
            'query' => [
                //'cmd' => 'sendmsg',
                'cmd' => $cmd,
                'sessionid' => $this->sessionId,
                'message' => $message,
                'sender' => $sender, //DND_BYPASSSLyman
                'sendto' => $receipient,
                'msgtype' => '0'
            ]
        ]);
        if($sendSMSResponse->getReasonPhrase() == 'OK'){
            $content = $sendSMSResponse->getBody()->getContents();
            return ltrim($content, 'OK: ');
        }else{
            throw new Error('Error While Sending SMS');
        }
    }

    public function getSMSData() {
        $now = Carbon::now();
        $filter = $this->request->input('filter', 'year');

        switch ($filter) {
            case 'month':
            $data = collect(range(1, $now->daysInMonth))->map(function($n)use($now){
                $now->month = $n;
                return [
                    'date' => $now->format('Y-m-d'),
                    'name' => $now->format('D'),
                    'fullName' => $now->format('l'),
                    'count' => 0
                ];
            });
            $smsData = SMSBatch::whereMonth('created_at', $now->month)
                ->select(\DB::raw('count(*) as total, created_at'))
                ->groupBy('created_at')
                ->get()
                ->groupBy(function($d){
                    return Carbon::parse($d->date)->format('m');
                });
            foreach ($smsData as $key => $value) {
                $date = Carbon::parse($value[0]['created_at']);
                $data[$key-1] = [
                            'date' => $date->format('Y-m-d'),
                            'name' => $date->format('D'),
                            'fullName' => $date->format('l'),
                            'count' => count($value)
                         ];
            }
            return ['data' => $data];
                break;
            
            default:
                $data = collect(range(1,12))->map(function($n)use($now){
                    $now->month = $n;
                    return [
                        'date' => $now->format('Y-m'),
                        'name' => $now->format('M'),
                        'fullName' => $now->format('F'),
                        'count' => 0
                    ];
                });
                $smsData = SMSBatch::whereYear('created_at', $now->year)
                    ->select(\DB::raw('count(*) as total, created_at'))
                    ->groupBy('created_at')
                    ->get()
                    ->groupBy(function($d){
                        return Carbon::parse($d->date)->format('m');
                    });
                foreach ($smsData as $key => $value) {
                    $date = Carbon::parse($value[0]['created_at']);
                    $data[$key-1] = [
                                'date' => $date->format('Y-m'),
                                'name' => $date->format('M'),
                                'fullName' => $date->format('F'),
                                'count' => count($value)
                             ];
                }
                return ['data' => $data];
                break;
        }
        
    }

    private function array_depth($array) {
        $max_depth = 1;
    
        foreach ($array as $value) {
            if (is_array($value)) {
                $depth = array_depth($value) + 1;
    
                if ($depth > $max_depth) {
                    $max_depth = $depth;
                }
            }
        }
    
        return $max_depth;
    }
}