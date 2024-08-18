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
            $table->string('role', 20)->default('Donator');
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
            $table->string('description', 255);
            $table->timestamps();
        });

        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('category_id');
            $table->string('payment_option', 50);
            $table->decimal('amount', 10, 2);
            $table->string('attachment_file')->nullable();
            $table->string('reference_no')->nullable();
            $table->string('approve_status')->default('pending');

            $table->timestamps();
            // Indexes
            $table->index('user_id');
            $table->index('category_id');
        });

        Schema::create('donation_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('donation_id');
            $table->string('status', 50);
            $table->timestamps();

            // Indexes
            $table->index('user_id');
            $table->index('donation_id');
        });

        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_type', 50);
            $table->date('period_start');
            $table->date('period_end');
            $table->timestamps();
        });

        Schema::create('fund_allocation', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->decimal('allocated_amount', 10, 2);
            $table->timestamps();

            // Indexes
            $table->index('category_id');
        });

        Schema::create('financial_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('report_id');
            $table->text('description');
            $table->timestamps();

            // Indexes
            $table->index('report_id');
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
        Schema::dropIfExists('payment_gateways');
        Schema::dropIfExists('receipts');
        Schema::dropIfExists('donation_history');
        Schema::dropIfExists('reports');
        Schema::dropIfExists('fund_allocation');
        Schema::dropIfExists('financial_reports');
    }
};
