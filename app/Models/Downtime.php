<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $start_time
 * @property string $end_time
 * @property string $created_at
 * @property string $updated_at
 *
 */
class Downtime extends Model
{
    use HasFactory;
}
