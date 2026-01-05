<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $tags = [
            // Language
            ['name' => 'CSS', 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
            ['name' => 'HTML', 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
            ['name' => 'Java', 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
            ['name' => 'JavaScript', 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
            ['name' => 'JSON', 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
            ['name' => 'PHP', 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
            ['name' => 'Python', 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
            ['name' => 'Swift', 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
            ['name' => 'TypeScript', 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
            ['name' => 'XML', 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
            // Backend Framework
            ['name' => 'Laravel', 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
            ['name' => 'Symfony', 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
            ['name' => 'Node.js', 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
            // Web Server
            ['name' => 'Apache', 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
            ['name' => 'Nginx', 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
            ['name' => 'Serverless', 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
            // Database,
            ['name' => 'MySQL', 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
            ['name' => 'MariaDB', 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
            ['name' => 'MSSQL', 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
            ['name' => 'PostgreSQL', 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
            ['name' => 'Redis', 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
            // Front-end Framework,
            ['name' => 'Vue.js', 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
            ['name' => 'React.js', 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
            ['name' => 'Angular.js', 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
            ['name' => 'Alpine.js', 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
            // CSS Framework
            ['name' => 'Bootstrap', 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
            ['name' => 'Tailwind CSS', 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
            // Operating System
            ['name' => 'Mac', 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
            ['name' => 'Linux', 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
            ['name' => 'Ubuntu', 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
            ['name' => 'Debian', 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
            ['name' => 'iOS', 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
            ['name' => 'Android', 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
            // Virtual Machine
            ['name' => 'VirtualBox', 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
            ['name' => 'VMware Workstation', 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
            ['name' => 'Docker', 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
            // Cloud
            ['name' => 'Amazon Web Services', 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
            ['name' => 'Microsoft Azure', 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
            ['name' => 'Google Cloud Platform', 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
            ['name' => 'Heroku', 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
            // Network Technology
            ['name' => 'Internet', 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
            ['name' => 'Internet Protocol Suite', 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
            ['name' => 'DNS (Domain Name System)', 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
            ['name' => 'Cookie', 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
            ['name' => 'Session', 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
            ['name' => 'Cache', 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
            // Software Tools
            ['name' => 'Git', 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
            ['name' => 'Sourcetree', 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
            ['name' => 'SSH (Secure Shell)', 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
            ['name' => 'Composer', 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
            ['name' => 'npm (Node Package Manager)', 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
            ['name' => 'CKEditor', 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
            // Source Code Hosting Platform
            ['name' => 'GitHub', 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
            ['name' => 'GitLab', 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
            ['name' => 'Bitbucket', 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
            // Brand
            ['name' => 'Google', 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
            ['name' => 'Microsoft', 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
            ['name' => 'Amazon', 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
            ['name' => 'Facebook', 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
            ['name' => 'Twitter', 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
            ['name' => 'Apple', 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
            ['name' => 'JetBrains', 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
        ];

        DB::table('tags')->insert($tags);
    }

    public function down(): void
    {
        DB::table('tags')->truncate();
    }
};
