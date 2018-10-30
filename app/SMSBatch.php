<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SMSBatch extends Model
{
    //
    protected $fillable = [
        'user_id',
        'batch_no',
        'status',
    ];

    protected $table = 'sms_batches';

    public function messages(){
        return $this->hasMany(SMS::class, 'batch_id');
    }

    public function messagesCount() {
        return $this->messages()->selectRaw('batch_id, count(*) as count')->groupBy('batch_id');
    }

    public function messagesCountFlat() {
        return $this->messages()->selectRaw('batch_id, count(*) as count')->groupBy('batch_id')->flatten();
    }
}
