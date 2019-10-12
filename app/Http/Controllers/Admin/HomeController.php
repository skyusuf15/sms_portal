<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use GuzzleHttp\Client;

use App\SMS;
use App\SMSBatch;

class HomeController extends Controller
{

    protected $sessionId = null;
    protected $client;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
        $this->client = new Client([
            'base_uri'=> 'http://www.smslive247.com/http/index.aspx'
        ]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $smsBatches = SMSBatch::count();
        $messages = SMS::count();
        return view('dashboard.home', compact('smsBatches', 'messages'));
    }

    public function getWalletBalance() {
        return number_format($this->getBalance() + 400000, 2, '.', ',');
        // return number_format(12233244.2424, 2, '.', ',');
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

    private function getBalance(){
        $this->sessionId = $this->getSessionId();
        // dd($this->sessionId);
        // return $this->sessionId;
        $sendSMSResponse =  $this->client->request('GET', '', [
            'query' => [
                'cmd' => 'querybalance',
                'sessionid' => $this->sessionId,
            ]
        ]);
        if($sendSMSResponse->getReasonPhrase() == 'OK'){
            $content = $sendSMSResponse->getBody()->getContents();
            return ltrim($content, 'OK: ');
        }else{
            throw new Error('Error While Sending SMS');
        }
    }
}
