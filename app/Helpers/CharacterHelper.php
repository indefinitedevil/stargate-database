<?php

namespace App\Helpers;

use App\Models\Character;
use App\Models\CharacterLog;
use App\Models\LogType;
use App\Models\Status;
use Illuminate\Support\Collection;

class CharacterHelper
{
    private static ?Collection $charactersWithoutDowntime = null;

    public static function getLowestTrainedMonths(): int
    {
        if (is_null(self::$charactersWithoutDowntime)) {
            self::$charactersWithoutDowntime = Character::leftJoin('character_logs', function ($join) {
                $join->on('characters.id', '=', 'character_logs.character_id')
                    ->where('character_logs.log_type_id', LogType::DOWNTIME);
            })
                ->whereIn('characters.status_id', [Status::PLAYED])
                ->whereNull('character_logs.id')
                ->get();
        }
        if (self::$charactersWithoutDowntime->count()) {
            return 0;
        }
        return self::getLowestDowntimeMonths();
    }

    public static function getLowestDowntimeMonths(): int
    {
        $logs = CharacterLog::where('log_type_id', LogType::DOWNTIME)
            ->join('characters', 'characters.id', '=', 'character_logs.character_id')
            ->whereIn('characters.status_id', [Status::APPROVED, Status::PLAYED])
            ->selectRaw('SUM(amount_trained) AS total')
            ->groupBy('character_id')
            ->orderBy('total', 'ASC')
            ->get();
        return $logs->count() ? $logs->first()->total : 0;
    }

    public static function getHighestTrainedMonths(): int
    {
        $logs = CharacterLog::where('log_type_id', LogType::DOWNTIME)
            ->join('characters', 'characters.id', '=', 'character_logs.character_id')
            ->whereIn('characters.status_id', [Status::APPROVED, Status::PLAYED])
            ->selectRaw('SUM(amount_trained) AS total')
            ->groupBy('character_id')
            ->orderBy('total', 'DESC')
            ->get();
        return $logs->count() ? $logs->first()->total : 0;
    }

    public static function getCatchupXP(): int
    {
        return self::getLowestDowntimeMonths();
    }
}
