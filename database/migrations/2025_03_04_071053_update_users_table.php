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
            $table->integer('invite_user_id')->nullable()->after('id');
            $table->bigInteger('telegram_id')->nullable()->after('invite_user_id');
            $table->char('password_algo', 10)->nullable()->after('password');
            $table->char('password_salt', 10)->nullable()->after('password_algo');
            $table->integer('balance')->default(0)->after('password_salt');
            $table->integer('discount')->nullable()->after('balance');
            $table->tinyInteger('commission_type')->default(0)->comment('0: system 1: period 2: onetime')->after('discount');
            $table->integer('commission_rate')->nullable()->after('commission_type');
            $table->integer('commission_balance')->default(0)->after('commission_rate');
            $table->integer('t')->default(0)->after('commission_balance');
            $table->bigInteger('u')->default(0)->after('t');
            $table->bigInteger('d')->default(0)->after('u');
            $table->bigInteger('transfer_enable')->default(0)->after('d');
            $table->tinyInteger('banned')->default(0)->after('transfer_enable');
            $table->tinyInteger('is_admin')->default(0)->after('banned');
            $table->integer('last_login_at')->nullable()->after('is_admin');
            $table->tinyInteger('is_staff')->default(0)->after('last_login_at');
            $table->integer('last_login_ip')->nullable()->after('is_staff');
            $table->uuid('uuid')->unique()->after('last_login_ip');
            $table->integer('group_id')->nullable()->after('uuid');
            $table->integer('plan_id')->nullable()->after('group_id');
            $table->integer('speed_limit')->nullable()->after('plan_id');
            $table->tinyInteger('remind_expire')->default(1)->after('speed_limit');
            $table->tinyInteger('remind_traffic')->default(1)->after('remind_expire');
            $table->char('token', 32)->after('remind_traffic');
            $table->bigInteger('expired_at')->default(0)->after('token');
            $table->text('remarks')->nullable()->after('expired_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'invite_user_id', 'telegram_id', 'password_algo', 'password_salt', 'balance',
                'discount', 'commission_type', 'commission_rate', 'commission_balance', 't',
                'u', 'd', 'transfer_enable', 'banned', 'is_admin', 'last_login_at',
                'is_staff', 'last_login_ip', 'uuid', 'group_id', 'plan_id', 'speed_limit',
                'remind_expire', 'remind_traffic', 'token', 'expired_at', 'remarks',
            ]);
        });
    }
};
