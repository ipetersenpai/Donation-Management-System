<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 50);
            $table->string('middle_name', 50)->nullable();
            $table->string('last_name', 50);
            $table->string('role', 20)->default('Member');
            $table->string('suffix')->nullable();
            $table->dateTime('birth_date');
            $table->string('contact_no', 11);
            $table->string('home_address', 255);
            $table->string('gender', 6);
            $table->string('email')->unique();
            $table->boolean('verified_status')->default(false);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        Schema::create('donation_categories', function (Blueprint $table) {
            $table->id();
            $table->string('category_name', 255);
            $table->string('description', 255)->nullable();
            $table->string('about', 500)->nullable();
            $table->string('link', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->string('non_member_full_name', 175)->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('category_id');
            $table->string('payment_option', 50);
            $table->decimal('amount', 40, 2);
            $table->string('reference_no');
            $table->timestamps();
            // Indexes
            $table->index('user_id');
            $table->index('category_id');
        });


        Schema::create('fund_allocation', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->string('category_name', 255);
            $table->string('project_name', 255);
            $table->decimal('allocated_amount', 40, 2);
            $table->timestamps();

            // Indexes
            $table->index('category_id');
        });

        Schema::create('user_histories', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('user_id'); // Reference to the original user
    $table->string('first_name', 50);
    $table->string('middle_name', 50)->nullable();
    $table->string('last_name', 50);
    $table->string('role', 20)->default('Member');
    $table->string('suffix')->nullable();
    $table->dateTime('birth_date');
    $table->string('contact_no', 11);
    $table->string('home_address', 255);
    $table->string('gender', 6);
    $table->string('email');
    $table->boolean('verified_status')->default(false);
    $table->enum('action', ['updated', 'deleted']); // Action type
    $table->timestamp('action_at'); // Time of the action
    $table->timestamps();
});



    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('donation_categories');
        Schema::dropIfExists('donations');
        Schema::dropIfExists('fund_allocation');
        Schema::dropIfExists('user_histories');

    }
};
