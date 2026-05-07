<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('domains', function (Blueprint $table) {
            $table->enum('type', ['subdomain', 'tld'])->default('subdomain')->after('domain');
            $table->boolean('is_primary')->default(false)->after('type');
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::table('domains', function (Blueprint $table) {
            $table->dropIndex(['type']);
            $table->dropColumn(['is_primary', 'type']);
        });
    }
};
