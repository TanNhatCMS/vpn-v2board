<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class UserMigrationTest extends TestCase
{
    use RefreshDatabase; // Xóa database sau mỗi lần test

    /** @test */
    public function users_table_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('users', [
                'id', 'invite_user_id', 'telegram_id', 'email', 'password',
                'password_algo', 'password_salt', 'balance', 'discount',
                'commission_type', 'commission_rate', 'commission_balance',
                't', 'u', 'd', 'transfer_enable', 'banned', 'is_admin',
                'last_login_at', 'is_staff', 'last_login_ip', 'uuid',
                'group_id', 'plan_id', 'speed_limit', 'remind_expire',
                'remind_traffic', 'token', 'expired_at', 'remarks',
                'created_at', 'updated_at',
            ]),
            'The users table does not have expected columns'
        );
    }
}
