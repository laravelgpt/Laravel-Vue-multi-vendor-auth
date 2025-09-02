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
            // Basic profile fields
            if (! Schema::hasColumn('users', 'username')) {
                $table->string('username')->unique()->nullable()->after('name');
            }
            if (! Schema::hasColumn('users', 'first_name')) {
                $table->string('first_name')->nullable()->after('username');
            }
            if (! Schema::hasColumn('users', 'last_name')) {
                $table->string('last_name')->nullable()->after('first_name');
            }
            if (! Schema::hasColumn('users', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable()->after('last_name');
            }
            if (! Schema::hasColumn('users', 'gender')) {
                $table->enum('gender', ['male', 'female', 'other', 'prefer_not_to_say'])->nullable()->after('date_of_birth');
            }

            // Address fields
            if (! Schema::hasColumn('users', 'address_line_1')) {
                $table->string('address_line_1')->nullable()->after('gender');
            }
            if (! Schema::hasColumn('users', 'address_line_2')) {
                $table->string('address_line_2')->nullable()->after('address_line_1');
            }
            if (! Schema::hasColumn('users', 'city')) {
                $table->string('city')->nullable()->after('address_line_2');
            }
            if (! Schema::hasColumn('users', 'state')) {
                $table->string('state')->nullable()->after('city');
            }
            if (! Schema::hasColumn('users', 'postal_code')) {
                $table->string('postal_code')->nullable()->after('state');
            }
            if (! Schema::hasColumn('users', 'country')) {
                $table->string('country')->nullable()->after('postal_code');
            }

            // Professional fields
            if (! Schema::hasColumn('users', 'company')) {
                $table->string('company')->nullable()->after('country');
            }
            if (! Schema::hasColumn('users', 'job_title')) {
                $table->string('job_title')->nullable()->after('company');
            }
            if (! Schema::hasColumn('users', 'department')) {
                $table->string('department')->nullable()->after('job_title');
            }
            if (! Schema::hasColumn('users', 'employee_id')) {
                $table->string('employee_id')->nullable()->after('department');
            }

            // Preferences
            if (! Schema::hasColumn('users', 'timezone')) {
                $table->string('timezone')->default('UTC')->after('employee_id');
            }
            if (! Schema::hasColumn('users', 'language')) {
                $table->string('language')->default('en')->after('timezone');
            }
            if (! Schema::hasColumn('users', 'notification_preferences')) {
                $table->enum('notification_preferences', ['email', 'sms', 'push', 'all', 'none'])->default('email')->after('language');
            }

            // Additional profile fields
            if (! Schema::hasColumn('users', 'interests')) {
                $table->text('interests')->nullable()->after('notification_preferences');
            }
            if (! Schema::hasColumn('users', 'skills')) {
                $table->text('skills')->nullable()->after('interests');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = [
                'username',
                'first_name',
                'last_name',
                'date_of_birth',
                'gender',
                'address_line_1',
                'address_line_2',
                'city',
                'state',
                'postal_code',
                'country',
                'company',
                'job_title',
                'department',
                'employee_id',
                'timezone',
                'language',
                'notification_preferences',
                'interests',
                'skills',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
