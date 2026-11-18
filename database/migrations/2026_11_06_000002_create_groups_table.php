<?php

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
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        $now = now();

        DB::table('groups')->insert([
            ['name' => 'Home', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Work', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Family', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Friends', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('groups');
    }
};
