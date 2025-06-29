<?php

use App\Models\CharacterLog;
use App\Models\LogType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('character_logs', function (Blueprint $table) {
            $table->text('plot_notes')->nullable()->after('notes');
        });

        $logs = CharacterLog::where('log_type_id', LogType::PLOT)->whereNot('notes', '')->get();
        foreach ($logs as $log) {
            $log->plot_notes = $log->notes;
            $log->notes = 'E1 plot';
            $log->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('character_logs', function (Blueprint $table) {
            $table->dropColumn('plot_notes');
        });
    }
};
