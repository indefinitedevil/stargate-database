<?php

use App\Models\Event;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('event_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('event_id')->constrained();
            $table->foreignId('character_id')->nullable()->constrained();
            $table->boolean('attended')->default(false);
            $table->integer('role')->default(Event::ROLE_PLAYER);
            $table->timestamps();
        });
        $characterEvents =
            DB::table('character_event')
                ->join('characters', 'characters.id', '=', 'character_event.character_id')
                ->join('users', 'users.id', '=', 'characters.user_id')
                ->select(['character_id', 'event_id', 'users.id as user_id', 'role', 'attended', 'character_event.created_at AS created_at', 'character_event.updated_at AS updated_at'])
                ->get();
        foreach ($characterEvents as $characterEvent) {
            DB::table('event_user')->insert([
                'user_id' => $characterEvent->user_id,
                'event_id' => $characterEvent->event_id,
                'character_id' => $characterEvent->character_id,
                'attended' => $characterEvent->attended,
                'role' => $characterEvent->role,
                'created_at' => $characterEvent->created_at,
                'updated_at' => $characterEvent->updated_at,
            ]);
        }
        Schema::dropIfExists('character_event');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
