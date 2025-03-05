<?php

namespace App\Utils;

class CacheKey
{
    const KEYS = [
        'EMAIL_VERIFY_CODE' => 'email verification code',
        'LAST_SEND_EMAIL_VERIFY_TIMESTAMP' => 'the last time of email verification code sent',
        'SERVER_VMESS_ONLINE_USER' => 'node online user',
        'SERVER_VMESS_LAST_CHECK_AT' => 'the last check time of node',
        'SERVER_VMESS_LAST_PUSH_AT' => 'the last push time of node',
        'SERVER_TROJAN_ONLINE_USER' => 'trojan node online users',
        'SERVER_TROJAN_LAST_CHECK_AT' => 'the last check time of the trojan node',
        'SERVER_TROJAN_LAST_PUSH_AT' => 'the last push time of the trojan node',
        'SERVER_SHADOWSOCKS_ONLINE_USER' => 'ss node online user',
        'SERVER_SHADOWSOCKS_LAST_CHECK_AT' => 'the last check time of the ss node',
        'SERVER_SHADOWSOCKS_LAST_PUSH_AT' => 'the last push time of the ss node',
        'SERVER_HYSTERIA_ONLINE_USER' => 'hysteria node online user',
        'SERVER_HYSTERIA_LAST_CHECK_AT' => 'the last check time of the hysteria node',
        'SERVER_HYSTERIA_LAST_PUSH_AT' => 'the last push time of the hysteria node',
        'TEMP_TOKEN' => 'temporary token',
        'LAST_SEND_EMAIL_REMIND_TRAFFIC' => 'finally send traffic email reminder',
        'SCHEDULE_LAST_CHECK_AT' => 'plan the task last check time',
        'REGISTER_IP_RATE_LIMIT' => 'registration frequency limit',
        'LAST_SEND_LOGIN_WITH_MAIL_LINK_TIMESTAMP' => 'last time of sending login link',
        'PASSWORD_ERROR_LIMIT' => 'password error limit',
        'USER_SESSIONS' => 'user session',
    ];

    public static function get(string $key, $uniqueValue)
    {
        if (! in_array($key, array_keys(self::KEYS))) {
            abort(500, 'key is not in cache key list');
        }

        return $key.'_'.$uniqueValue;
    }
}
