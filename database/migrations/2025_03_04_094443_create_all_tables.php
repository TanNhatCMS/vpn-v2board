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
        Schema::create('server_group', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('server_hysteria', function (Blueprint $table) {
            $table->id();
            $table->string('group_id');
            $table->string('route_id')->nullable();
            $table->string('name');
            $table->integer('parent_id')->nullable();
            $table->string('host');
            $table->string('port', 11);
            $table->integer('server_port');
            $table->string('tags')->nullable();
            $table->string('rate', 11);
            $table->boolean('show')->default(0);
            $table->integer('sort')->nullable();
            $table->integer('up_mbps');
            $table->integer('down_mbps');
            $table->string('server_name', 64)->nullable();
            $table->boolean('insecure')->default(0);
            $table->timestamps();
        });

        Schema::create('server_route', function (Blueprint $table) {
            $table->id();
            $table->string('remarks');
            $table->text('match');
            $table->string('action', 11);
            $table->string('action_value')->nullable();
            $table->timestamps();
        });

        Schema::create('server_shadowsocks', function (Blueprint $table) {
            $table->id();
            $table->string('group_id');
            $table->string('route_id')->nullable();
            $table->integer('parent_id')->nullable();
            $table->string('tags')->nullable();
            $table->string('name');
            $table->string('rate', 11);
            $table->string('host');
            $table->string('port', 11);
            $table->integer('server_port');
            $table->string('cipher');
            $table->char('obfs', 11)->nullable();
            $table->string('obfs_settings')->nullable();
            $table->boolean('show')->default(0);
            $table->integer('sort')->nullable();
            $table->timestamps();
        });

        Schema::create('server_trojan', function (Blueprint $table) {
            $table->id();
            $table->string('group_id');
            $table->string('route_id')->nullable();
            $table->integer('parent_id')->nullable();
            $table->string('tags')->nullable();
            $table->string('name');
            $table->string('rate', 11);
            $table->string('host');
            $table->string('port', 11);
            $table->integer('server_port');
            $table->boolean('allow_insecure')->default(0);
            $table->string('server_name')->nullable();
            $table->boolean('show')->default(0);
            $table->integer('sort')->nullable();
            $table->timestamps();
        });

        Schema::create('server_vmess', function (Blueprint $table) {
            $table->id();
            $table->string('group_id');
            $table->string('route_id')->nullable();
            $table->string('name');
            $table->integer('parent_id')->nullable();
            $table->string('host');
            $table->string('port', 11);
            $table->integer('server_port');
            $table->boolean('tls')->default(0);
            $table->string('tags')->nullable();
            $table->string('rate', 11);
            $table->string('network', 11);
            $table->text('rules')->nullable();
            $table->text('networkSettings')->nullable();
            $table->text('tlsSettings')->nullable();
            $table->text('ruleSettings')->nullable();
            $table->text('dnsSettings')->nullable();
            $table->boolean('show')->default(0);
            $table->integer('sort')->nullable();
            $table->timestamps();
        });

        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('subject', 255);
            $table->tinyInteger('level');
            $table->tinyInteger('status')->default(0)->comment('0: Opened, 1: Closed');
            $table->tinyInteger('reply_status')->default(1)->comment('0: Waiting for reply, 1: Replied');
            $table->timestamps();
        });

        Schema::create('ticket_message', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('ticket_id')->constrained('tickets')->onDelete('cascade');
            $table->text('message');
            $table->timestamps();
        });

        Schema::create('coupon', function (Blueprint $table) {
            $table->id();
            $table->string('code', 255);
            $table->string('name', 255);
            $table->tinyInteger('type');
            $table->integer('value');
            $table->tinyInteger('show')->default(0);
            $table->integer('limit_use')->nullable();
            $table->integer('limit_use_with_user')->nullable();
            $table->string('limit_plan_ids', 255)->nullable();
            $table->string('limit_period', 255)->nullable();
            $table->integer('started_at');
            $table->integer('ended_at');
            $table->timestamps();
        });

        Schema::create('commission_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('invite_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->char('trade_no', 36);
            $table->integer('order_amount');
            $table->integer('get_amount');
            $table->timestamps();
        });

        Schema::create('stat', function (Blueprint $table) {
            $table->id();
            $table->integer('record_at')->unique()->comment('record time');
            $table->char('record_type', 1);
            $table->integer('order_count')->comment('order quantity');
            $table->integer('order_total')->comment('total orders');
            $table->integer('commission_count');
            $table->integer('commission_total')->comment('total commission');
            $table->integer('paid_count');
            $table->integer('paid_total');
            $table->integer('register_count');
            $table->integer('invite_count');
            $table->string('transfer_used_total', 32);
            $table->timestamps();
        });

        Schema::create('stat_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->decimal('server_rate', 10, 2);
            $table->bigInteger('u');
            $table->bigInteger('d');
            $table->char('record_type', 2);
            $table->integer('record_at');
            $table->timestamps();
            $table->unique(['server_rate', 'user_id', 'record_at'], 'server_rate_user_id_record_at');
        });

        Schema::create('stat_server', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('server_id')->comment('node id');
            $table->char('server_type', 11)->comment('node type');
            $table->bigInteger('u');
            $table->bigInteger('d');
            $table->char('record_type', 1)->comment('d day m month');
            $table->integer('record_at')->comment('record time');
            $table->timestamps();
            $table->unique(['server_id', 'server_type', 'record_at'], 'server_id_server_type_record_at');
        });

        Schema::create('invite_code', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->char('code', 32)->unique();
            $table->tinyInteger('status')->default(0);
            $table->integer('pv')->default(0);
            $table->timestamps();
        });
        // Bảng knowledge
        Schema::create('knowledge', function (Blueprint $table) {
            $table->id();
            $table->char('language', 5);
            $table->string('category')->comment('category name');
            $table->string('title');
            $table->text('body')->comment('content');
            $table->integer('sort')->nullable();
            $table->tinyInteger('show')->default(0);
            $table->timestamps();
        });

        // Bảng log
        Schema::create('log', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('level', 11)->nullable();
            $table->string('host')->nullable();
            $table->string('uri');
            $table->string('method', 11);
            $table->text('data')->nullable();
            $table->string('ip', 128)->nullable();
            $table->text('context')->nullable();
            $table->timestamps();
        });

        // Bảng mail_log
        Schema::create('mail_log', function (Blueprint $table) {
            $table->id();
            $table->string('email', 64);
            $table->string('subject');
            $table->string('template_name');
            $table->text('error')->nullable();
            $table->timestamps();
        });

        // Bảng notice
        Schema::create('notice', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->tinyInteger('show')->default(0);
            $table->string('img_url')->nullable();
            $table->string('tags')->nullable();
            $table->timestamps();
        });

        // Bảng payment
        Schema::create('payment', function (Blueprint $table) {
            $table->id();
            $table->char('uuid', 32);
            $table->string('payment', 16);
            $table->string('name');
            $table->string('icon')->nullable();
            $table->text('config');
            $table->string('notify_domain', 128)->nullable();
            $table->integer('handling_fee_fixed')->nullable();
            $table->decimal('handling_fee_percent', 5, 2)->nullable();
            $table->tinyInteger('enable')->default(0);
            $table->integer('sort')->nullable();
            $table->timestamps();
        });

        // Bảng plan
        Schema::create('plan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_id');
            $table->integer('transfer_enable');
            $table->string('name');
            $table->integer('speed_limit')->nullable();
            $table->tinyInteger('show')->default(0);
            $table->integer('sort')->nullable();
            $table->tinyInteger('renew')->default(1);
            $table->text('content')->nullable();
            $table->integer('month_price')->nullable();
            $table->integer('quarter_price')->nullable();
            $table->integer('half_year_price')->nullable();
            $table->integer('year_price')->nullable();
            $table->integer('two_year_price')->nullable();
            $table->integer('three_year_price')->nullable();
            $table->integer('onetime_price')->nullable();
            $table->integer('reset_price')->nullable();
            $table->tinyInteger('reset_traffic_method')->nullable();
            $table->integer('capacity_limit')->nullable();
            $table->timestamps();
        });
        // Bảng order
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invite_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('plan_id')->constrained('plan')->onDelete('cascade');
            $table->foreignId('coupon_id')->nullable()->constrained('coupon')->onDelete('set null');
            $table->foreignId('payment_id')->nullable()->constrained('payment')->onDelete('set null');
            $table->integer('type')->comment('1 new purchase 2 renewal 3 upgrade');
            $table->string('period');
            $table->string('trade_no', 36)->unique();
            $table->string('callback_no')->nullable();
            $table->integer('total_amount');
            $table->integer('handling_amount')->nullable();
            $table->integer('discount_amount')->nullable();
            $table->integer('surplus_amount')->nullable()->comment('remaining value');
            $table->integer('refund_amount')->nullable()->comment('refund amount');
            $table->integer('balance_amount')->nullable()->comment('use balance');
            $table->text('surplus_order_ids')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0 to be paid 1 is being activated 2 Canceled 3 Completed 4 has been discounted');
            $table->tinyInteger('commission_status')->default(0)->comment('0 to be confirmed 1 is being distributed 2 Valid 3 Invalid');
            $table->integer('commission_balance')->default(0);
            $table->integer('actual_commission_balance')->nullable()->comment('actual commission payment');
            $table->integer('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('server_group');
        Schema::dropIfExists('server_hysteria');
        Schema::dropIfExists('server_route');
        Schema::dropIfExists('server_shadowsocks');
        Schema::dropIfExists('server_trojan');
        Schema::dropIfExists('server_vmess');
        Schema::dropIfExists('tickets');
        Schema::dropIfExists('ticket_message');
        Schema::dropIfExists('coupon');
        Schema::dropIfExists('commission_log');
        Schema::dropIfExists('stat');
        Schema::dropIfExists('stat_user');
        Schema::dropIfExists('stat_server');
        Schema::dropIfExists('invite_code');
        Schema::dropIfExists('knowledge');
        Schema::dropIfExists('log');
        Schema::dropIfExists('mail_log');
        Schema::dropIfExists('notice');
        Schema::dropIfExists('payment');
        Schema::dropIfExists('plan');
        Schema::dropIfExists('orders');
    }
};
