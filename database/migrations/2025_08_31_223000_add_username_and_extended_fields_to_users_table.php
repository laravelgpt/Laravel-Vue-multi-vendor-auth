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
            // Add username field (nullable first, then we'll update existing users)
            $table->string('username')->nullable()->unique()->after('name');

            // Add extended user information fields
            $table->string('first_name')->nullable()->after('username');
            $table->string('last_name')->nullable()->after('first_name');
            $table->date('date_of_birth')->nullable()->after('last_name');
            $table->enum('gender', ['male', 'female', 'other', 'prefer_not_to_say'])->nullable()->after('date_of_birth');

            // Address information
            $table->string('address_line_1')->nullable()->after('gender');
            $table->string('address_line_2')->nullable()->after('address_line_1');
            $table->string('city')->nullable()->after('address_line_2');
            $table->string('state')->nullable()->after('city');
            $table->string('postal_code')->nullable()->after('state');
            $table->string('country')->nullable()->after('postal_code');

            // Additional contact information
            $table->string('alternate_email')->nullable()->after('country');
            $table->string('emergency_contact')->nullable()->after('alternate_email');
            $table->string('emergency_phone')->nullable()->after('emergency_contact');

            // Professional information
            $table->string('company')->nullable()->after('emergency_phone');
            $table->string('job_title')->nullable()->after('company');
            $table->string('department')->nullable()->after('job_title');
            $table->string('employee_id')->nullable()->after('department');

            // Preferences and settings
            $table->string('timezone')->default('UTC')->after('employee_id');
            $table->string('language')->default('en')->after('timezone');
            $table->enum('notification_preferences', ['email', 'sms', 'push', 'all', 'none'])->default('email')->after('language');

            // Social media and additional links
            $table->string('facebook')->nullable()->after('notification_preferences');
            $table->string('instagram')->nullable()->after('facebook');
            $table->string('youtube')->nullable()->after('instagram');
            $table->string('tiktok')->nullable()->after('youtube');

            // Additional profile fields
            $table->text('interests')->nullable()->after('tiktok');
            $table->text('skills')->nullable()->after('interests');
            $table->text('education')->nullable()->after('skills');
            $table->text('experience')->nullable()->after('education');

            // Account verification and status
            $table->boolean('email_verified')->default(false)->after('experience');
            $table->boolean('phone_verified')->default(false)->after('email_verified');
            $table->timestamp('last_login_at')->nullable()->after('phone_verified');
            $table->string('registration_ip')->nullable()->after('last_login_at');
            $table->string('last_login_ip')->nullable()->after('registration_ip');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
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
                'alternate_email',
                'emergency_contact',
                'emergency_phone',
                'company',
                'job_title',
                'department',
                'employee_id',
                'timezone',
                'language',
                'notification_preferences',
                'facebook',
                'instagram',
                'youtube',
                'tiktok',
                'interests',
                'skills',
                'education',
                'experience',
                'email_verified',
                'phone_verified',
                'last_login_at',
                'registration_ip',
                'last_login_ip',
            ]);
        });
    }
};
