<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property string name
 */
class LogType extends Model
{
    use HasFactory;

    const CHARACTER_CREATION = 1;
    const DOWNTIME = 2;
    const PLOT = 3;
    const SYSTEM = 4;
}
