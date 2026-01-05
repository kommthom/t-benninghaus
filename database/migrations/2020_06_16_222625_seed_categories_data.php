<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $categories = [
            [
                'name' => 'Tägliches Teilen',
                'icon' => 'bi-chat-dots-fill',
                'description' => 'Erzähl von deinen Themen',
            ],
            [
                'name' => 'Programmiertechniken',
                'icon' => 'bi-terminal-fill',
                'description' => 'Austausch und Teilen von Programmiertechniken',
            ],
            [
                'name' => 'Musik',
                'icon' => 'bi bi-megaphone',
                'description' => 'Heavy Metal und andere Musik, die ich mag',
            ],
            [
                'name' => 'Draussen',
                'icon' => 'bi bi-cloud-check-fill',
                'description' => 'Wandern, Radfahren, Natur bewundern',
            ],
            [
                'name' => 'Privat',
                'icon' => 'bi bi-envelope-exclamation',
                'description' => 'Privates',
            ],
        ];

        DB::table('categories')->insert($categories);
    }

    public function down(): void
    {
        DB::table('categories')->truncate();
    }
};
