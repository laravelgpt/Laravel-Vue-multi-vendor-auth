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
        // For SQLite, we need to recreate the table to change the enum constraint
        if (DB::getDriverName() === 'sqlite') {
            // Create a temporary table with the new structure
            Schema::create('users_temp', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->rememberToken();
                $table->timestamps();
                $table->enum('role', ['admin', 'wholeseller', 'customer'])->default('customer');
                $table->string('avatar')->nullable();
                $table->string('phone')->nullable();
                $table->boolean('is_active')->default(true);
                $table->text('bio')->nullable();
                $table->string('location')->nullable();
                $table->string('website')->nullable();
                $table->string('twitter')->nullable();
                $table->string('linkedin')->nullable();
                $table->string('github')->nullable();
                $table->timestamp('last_login_at')->nullable();
                $table->string('username')->unique();
                $table->string('country_code')->nullable();
                $table->text('address')->nullable();
                $table->string('country')->nullable();
                $table->string('state')->nullable();
                $table->string('city')->nullable();
                $table->string('zip_code')->nullable();
                $table->boolean('is_admin')->default(false);
            });

            // Copy data from old table to new table, converting 'user' to 'customer'
            DB::statement("
                INSERT INTO users_temp (
                    id, name, email, email_verified_at, password, remember_token, created_at, updated_at,
                    role, avatar, phone, is_active, bio, location, website, twitter, linkedin, github,
                    last_login_at, username, country_code, address, country, state, city, zip_code, is_admin
                )
                SELECT 
                    id, name, email, email_verified_at, password, remember_token, created_at, updated_at,
                    CASE WHEN role = 'user' THEN 'customer' ELSE role END,
                    avatar, phone, is_active, bio, location, website, twitter, linkedin, github,
                    last_login_at, username, country_code, address, country, state, city, zip_code, is_admin
                FROM users
            ");

            // Drop the old table and rename the new one
            Schema::drop('users');
            Schema::rename('users_temp', 'users');
        } else {
            // For other databases, use the original approach
            DB::table('users')->where('role', 'user')->update(['role' => 'customer']);

            Schema::table('users', function (Blueprint $table) {
                $table->enum('role', ['admin', 'wholeseller', 'customer'])->default('customer')->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            // Create a temporary table with the old structure
            Schema::create('users_temp', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->rememberToken();
                $table->timestamps();
                $table->enum('role', ['user', 'admin'])->default('user');
                $table->string('avatar')->nullable();
                $table->string('phone')->nullable();
                $table->boolean('is_active')->default(true);
                $table->text('bio')->nullable();
                $table->string('location')->nullable();
                $table->string('website')->nullable();
                $table->string('twitter')->nullable();
                $table->string('linkedin')->nullable();
                $table->string('github')->nullable();
                $table->timestamp('last_login_at')->nullable();
                $table->string('username')->unique();
                $table->string('country_code')->nullable();
                $table->text('address')->nullable();
                $table->string('country')->nullable();
                $table->string('state')->nullable();
                $table->string('city')->nullable();
                $table->string('zip_code')->nullable();
                $table->boolean('is_admin')->default(false);
            });

            // Copy data from new table to old table, converting 'customer' back to 'user'
            DB::statement("
                INSERT INTO users_temp (
                    id, name, email, email_verified_at, password, remember_token, created_at, updated_at,
                    role, avatar, phone, is_active, bio, location, website, twitter, linkedin, github,
                    last_login_at, username, country_code, address, country, state, city, zip_code, is_admin
                )
                SELECT 
                    id, name, email, email_verified_at, password, remember_token, created_at, updated_at,
                    CASE WHEN role = 'customer' THEN 'user' ELSE role END,
                    avatar, phone, is_active, bio, location, website, twitter, linkedin, github,
                    last_login_at, username, country_code, address, country, state, city, zip_code, is_admin
                FROM users
            ");

            // Drop the new table and rename the old one
            Schema::drop('users');
            Schema::rename('users_temp', 'users');
        } else {
            // For other databases, use the original approach
            DB::table('users')->where('role', 'customer')->update(['role' => 'user']);

            Schema::table('users', function (Blueprint $table) {
                $table->enum('role', ['user', 'admin'])->default('user')->change();
            });
        }
    }
};
