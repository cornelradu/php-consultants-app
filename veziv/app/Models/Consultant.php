<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Consultant extends Model
{
    protected $table = 'Consultants';
    protected $primary_key = 'ConsultantsId';
    public $incrementing = true;
    
    protected $fillable = [
        'firstName',
        'lastName',
    ];

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'consultant_id', 'id');
    }
}
