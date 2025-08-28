<?php

namespace App\Helpers;

use App\Models\Character;
use App\Models\CharacterLog;
use App\Models\LogType;
use App\Models\Status;
use Illuminate\Support\Collection;

class CharacterHelper
{
    public static function getLowestTrainingMonths(): int
    {
        $logs = CharacterLog::join('characters', 'characters.id', '=', 'character_logs.character_id')
            ->whereIn('characters.status_id', [Status::APPROVED, Status::PLAYED])
            ->selectRaw('SUM(amount_trained) AS total')
            ->groupBy('character_id')
            ->orderBy('total', 'ASC')
            ->get();
        return $logs->count() ? $logs->first()->total : 0;
    }

    public static function getLowestTrainingMonthsCharacterId(): int
    {
        $logs = CharacterLog::join('characters', 'characters.id', '=', 'character_logs.character_id')
            ->join('backgrounds', 'backgrounds.id', '=', 'characters.background_id')
            ->whereIn('characters.status_id', [Status::APPROVED, Status::PLAYED])
            ->selectRaw('SUM(amount_trained) - backgrounds.months AS total, character_id')
            ->groupBy('character_id', 'backgrounds.months')
            ->orderBy('total', 'ASC')
            ->get();
        return $logs->count() ? $logs->first()->character_id : 0;
    }

    public static function getLowestTrainingMonthsIncludingDowntime(): int
    {
        $logs = CharacterLog::join('characters', 'characters.id', '=', 'character_logs.character_id')
            ->whereIn('characters.status_id', [Status::APPROVED, Status::PLAYED])
            ->whereIn('characters.id', self::getCharacterIdsWithDowntimes())
            ->selectRaw('SUM(amount_trained) AS total')
            ->groupBy('character_id')
            ->orderBy('total', 'ASC')
            ->get();
        return $logs->count() ? $logs->first()->total : 0;
    }

    public static function getLowestPostCreationTrainingMonthsIncludingDowntime(): int
    {
        $logs = CharacterLog::join('characters', 'characters.id', '=', 'character_logs.character_id')
            ->join('backgrounds', 'backgrounds.id', '=', 'characters.background_id')
            ->whereIn('characters.status_id', [Status::APPROVED, Status::PLAYED])
            ->whereIn('characters.id', self::getCharacterIdsWithDowntimes())
            ->selectRaw('SUM(amount_trained) - backgrounds.months AS total')
            ->groupBy('character_id', 'backgrounds.months')
            ->orderBy('total', 'ASC')
            ->get();
        return $logs->count() ? $logs->first()->total : 0;
    }

    public static function getLowestPostCreationTrainingMonthsIncludingDowntimeCharacterId(): int
    {
        $logs = CharacterLog::join('characters', 'characters.id', '=', 'character_logs.character_id')
            ->join('backgrounds', 'backgrounds.id', '=', 'characters.background_id')
            ->whereIn('characters.status_id', [Status::APPROVED, Status::PLAYED])
            ->whereIn('characters.id', self::getCharacterIdsWithDowntimes())
            ->selectRaw('SUM(amount_trained) - backgrounds.months AS total, character_id')
            ->groupBy('character_id', 'backgrounds.months')
            ->orderBy('total', 'ASC')
            ->get();
        return $logs->count() ? $logs->first()->character_id : 0;
    }

    public static function getHighestTrainingMonths(): int
    {
        $logs = CharacterLog::join('characters', 'characters.id', '=', 'character_logs.character_id')
            ->whereIn('characters.status_id', [Status::APPROVED, Status::PLAYED])
            ->selectRaw('SUM(amount_trained) AS total')
            ->groupBy('character_id')
            ->orderBy('total', 'DESC')
            ->get();
        return $logs->count() ? $logs->first()->total : 0;
    }

    public static function getCharacterIdsWithDowntimes(): array
    {
        return CharacterLog::join('characters', 'characters.id', '=', 'character_logs.character_id')
            ->where('character_logs.log_type_id', LogType::DOWNTIME)
            ->whereIn('characters.status_id', [Status::APPROVED, Status::PLAYED])
            ->pluck('character_logs.character_id')
            ->toArray();
    }

    public static function getCatchupXP(): int
    {
        return self::getLowestPostCreationTrainingMonthsIncludingDowntime();
    }

    public static function getCharacterById(int $characterId): ?Character
    {
        static $characters = [];
        if (!isset($characters[$characterId])) {
            $characters[$characterId] = Character::find($characterId);
        }
        return $characters[$characterId];
    }
}
