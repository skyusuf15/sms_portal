<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\SMS;
use App\SMSBatch;
use App\SmsHistory;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    protected $sessionId = null;
    protected $client;
    protected $prefix_count= [];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
        $this->client = new Client([
            'base_uri' => 'http://www.smslive247.com/http/index.aspx',
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

    public function getWalletBalance()
    {
        return number_format($this->getBalance() + 1055933, 2, '.', ',');
        // return number_format(12233244.2424, 2, '.', ',');
    }

    public function getTelcosCount()
    {
        $json_string = file_get_contents(storage_path('app') . "/telcos_prefix.json");
        $prefix_json = json_decode($json_string, true);
        
        SmsHistory::chunk(100, function ($histories) use ($prefix_json) {
            foreach ($histories as $history) {
                $tempNumber = substr($history->MobileNumber, 3, strlen($history->MobileNumber));
                foreach ($prefix_json as $json_object) {
                    if ($this->has_prefix($tempNumber, $json_object['Prefix'])) {

                        if (array_key_exists($json_object['Network'], $this->prefix_count)) {
                            $this->prefix_count[$json_object['Network']]['network_count'] = $this->prefix_count[$json_object['Network']]['network_count'] + 1;
                            $this->prefix_count[$json_object['Network']]['unit_charge'] += $history->UnitsCharged;
                        }
                        else {
                            $this->prefix_count[$json_object['Network']] =[];
                            $this->prefix_count[$json_object['Network']]['network_count'] = 1;
                            $this->prefix_count[$json_object['Network']]['price_per_unit'] = $json_object['price_per_unit'];
                            $this->prefix_count[$json_object['Network']]['unit_charge'] = $history->UnitsCharged;

                        }
                        break;
                    }
                }
            }
        });
        return $this->prefix_count;
    }

    private function has_prefix($string, $prefix)
    {
        return substr($string, 0, strlen($prefix)) == $prefix;
    }

    private function getSessionId()
    {
        if (is_null($this->sessionId)) {
            $authResponse = $this->client->request('GET', '', [
                'query' => [
                    'cmd' => 'login',
                    'OwnerEmail' => env('SMS_LIVE_EMAIL', 'damilola.dammie@gmail.com'),
                    'SubAcct' => env('SMS_LIVE_SUB_ACCOUNT', 'Ibiton_Test'),
                    'SubAcctPwd' => env('SMS_LIVE_PASSWORD', 'Pasme@123!'),
                ],
            ]);
            // dd($authResponse);
            if ($authResponse->getReasonPhrase() == 'OK') {
                $content = $authResponse->getBody()->getContents();
                return ltrim($content, 'OK: ');
            } else {
                throw new Error('Error While Getting Session ID');
            }
        } else {
            return $this->sessionId;
        }
    }

    private function getBalance()
    {
        $this->sessionId = $this->getSessionId();
        // dd($this->sessionId);
        // return $this->sessionId;
        $sendSMSResponse = $this->client->request('GET', '', [
            'query' => [
                'cmd' => 'querybalance',
                'sessionid' => $this->sessionId,
            ],
        ]);
        if ($sendSMSResponse->getReasonPhrase() == 'OK') {
            $content = $sendSMSResponse->getBody()->getContents();
            return ltrim($content, 'OK: ');
        } else {
            throw new Error('Error While Sending SMS');
        }
    }
}
