<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CpcItem extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['item_type', 'date', 'name', 'topics', 'key_learning_outcomes', 'points', 'practice_change', 'attachment', 'attachment_name'];

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }
}
