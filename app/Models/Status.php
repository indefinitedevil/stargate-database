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
    const READY = 2;
    const APPROVED = 3;
    const PLAYED = 4;
    const DEAD = 5;
    const RETIRED = 6;
    const INACTIVE = 7;
}
