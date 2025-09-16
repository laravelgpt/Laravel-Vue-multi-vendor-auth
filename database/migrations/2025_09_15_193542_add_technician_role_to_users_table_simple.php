<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For SQLite, we need to recreate the table due to enum limitations
        if (DB::getDriverName() === 'sqlite') {
            // Drop any existing temporary tables
            Schema::dropIfExists('users_new');
            Schema::dropIfExists('users_old');

            // Create new table with technician role
            DB::statement('CREATE TABLE users_new (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) UNIQUE NOT NULL,
                email_verified_at DATETIME,
                password VARCHAR(255) NOT NULL,
                remember_token VARCHAR(100),
                created_at DATETIME,
                updated_at DATETIME,
                role VARCHAR(255) DEFAULT "customer" CHECK (role IN ("admin", "wholeseller", "customer", "technician")),
                avatar VARCHAR(255),
                phone VARCHAR(255),
                is_active TINYINT DEFAULT 1,
                bio TEXT,
                location VARCHAR(255),
                website VARCHAR(255),
                twitter VARCHAR(255),
                linkedin VARCHAR(255),
                github VARCHAR(255),
                last_login_at DATETIME,
                username VARCHAR(255) UNIQUE NOT NULL,
                country_code VARCHAR(255),
                address TEXT,
                country VARCHAR(255),
                state VARCHAR(255),
                city VARCHAR(255),
                zip_code VARCHAR(255),
                is_admin TINYINT DEFAULT 0
            )');

            // Copy data from old table
            DB::statement('INSERT INTO users_new SELECT * FROM users');

            // Drop old table and rename new table
            Schema::drop('users');
            DB::statement('ALTER TABLE users_new RENAME TO users');
        } else {
            // For other databases, update the enum directly
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'wholeseller', 'customer', 'technician') DEFAULT 'customer'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // For SQLite, we need to recreate the table without technician role
        if (DB::getDriverName() === 'sqlite') {
            // Create new table without technician role
            DB::statement('CREATE TABLE users_old (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) UNIQUE NOT NULL,
                email_verified_at DATETIME,
                password VARCHAR(255) NOT NULL,
                remember_token VARCHAR(100),
                created_at DATETIME,
                updated_at DATETIME,
                role VARCHAR(255) DEFAULT "customer" CHECK (role IN ("admin", "wholeseller", "customer")),
                avatar VARCHAR(255),
                phone VARCHAR(255),
                is_active TINYINT DEFAULT 1,
                bio TEXT,
                location VARCHAR(255),
                website VARCHAR(255),
                twitter VARCHAR(255),
                linkedin VARCHAR(255),
                github VARCHAR(255),
                last_login_at DATETIME,
                username VARCHAR(255) UNIQUE NOT NULL,
                country_code VARCHAR(255),
                address TEXT,
                country VARCHAR(255),
                state VARCHAR(255),
                city VARCHAR(255),
                zip_code VARCHAR(255),
                is_admin TINYINT DEFAULT 0
            )');

            // Copy data from old table (excluding technicians)
            DB::statement('INSERT INTO users_old SELECT * FROM users WHERE role != "technician"');

            // Drop old table and rename new table
            Schema::drop('users');
            DB::statement('ALTER TABLE users_old RENAME TO users');
        } else {
            // For other databases, update the enum directly
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'wholeseller', 'customer') DEFAULT 'customer'");
        }
    }
};
