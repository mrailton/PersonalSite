<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatientContact extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['date', 'incident_number', 'organisation', 'injury', 'treatment'];

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }
}
