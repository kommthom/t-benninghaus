<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $categories = [
            [
                'name' => 'daily sharing',
                'icon' => 'bi-chat-dots-fill',
                'description' => 'talk about whatever you want',
            ],
            [
                'name' => 'programming technology',
                'icon' => 'bi-terminal-fill',
                'description' => 'programming technology exchange and sharing',
            ],
            [
                'name' => 'video game',
                ' icon' => 'bi bi-dpad-fill',
                'description' => 'video game topics and experiences',
            ],
        ];

        DB::table('categories')->insert($categories);
    }

    public function down(): void
    {
        DB::table('categories')->truncate();
    }
};
