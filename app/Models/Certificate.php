<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Certificate extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name', 'issued_by', 'issued_on', 'expires_on', 'image', 'notes'];

    protected $casts = [
        'issued_on' => 'date',
        'expires_on' => 'date',
    ];
}
