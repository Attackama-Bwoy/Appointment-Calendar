<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'appointment_id';

    protected $fillable = [
        'reason',
        'begin',
        'start',
        'end',
        'session',
        'booked_by',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}