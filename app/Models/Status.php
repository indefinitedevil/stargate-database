<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $name
 */
class Status extends Model
{
    const NEW = 1;
    const PLAYED = 2;
    const DEAD = 3;
    const RETIRED = 4;
}
