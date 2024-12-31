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
    const APPROVED = 2;
    const PLAYED = 3;
    const DEAD = 4;
    const RETIRED = 5;
}
