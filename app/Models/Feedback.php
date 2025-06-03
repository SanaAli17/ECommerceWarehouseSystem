<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $fillable = ['order_id', 'feedback'];
    
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }
}
