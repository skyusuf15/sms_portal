<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SMS extends Model
{
    //
    protected $fillable = [
            'user_id',
            'batch_id',
            'messageId',
            'sender',
            'receipient',
            'message',
            'status'
    ];

    protected $table = 'sms';

    public function batch(){
        return $this->belongsTo(SMSBatch::class, 'batch_id');
    }
}
