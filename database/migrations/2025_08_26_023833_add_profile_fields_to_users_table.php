<?php

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
        Schema::table('users', function (Blueprint $table) {
            $table->text('bio')->nullable()->after('is_active');
            $table->string('location')->nullable()->after('bio');
            $table->string('website')->nullable()->after('location');
            $table->string('twitter')->nullable()->after('website');
            $table->string('linkedin')->nullable()->after('twitter');
            $table->string('github')->nullable()->after('linkedin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['bio', 'location', 'website', 'twitter', 'linkedin', 'github']);
        });
    }
};
