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
            // Add only the fields that don't already exist
            if (! Schema::hasColumn('users', 'username')) {
                $table->string('username')->unique()->nullable()->after('name');
            }
            if (! Schema::hasColumn('users', 'country_code')) {
                $table->string('country_code', 5)->default('+1')->after('phone');
            }
            if (! Schema::hasColumn('users', 'address')) {
                $table->text('address')->nullable()->after('country_code');
            }
            if (! Schema::hasColumn('users', 'country')) {
                $table->string('country')->nullable()->after('address');
            }
            if (! Schema::hasColumn('users', 'state')) {
                $table->string('state')->nullable()->after('country');
            }
            if (! Schema::hasColumn('users', 'city')) {
                $table->string('city')->nullable()->after('state');
            }
            if (! Schema::hasColumn('users', 'zip_code')) {
                $table->string('zip_code')->nullable()->after('city');
            }
            if (! Schema::hasColumn('users', 'is_admin')) {
                $table->boolean('is_admin')->default(false)->after('is_active');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'username', 'country_code', 'address', 'country',
                'state', 'city', 'zip_code', 'is_admin',
            ]);
        });
    }
};
