<?php

namespace App\Utils;

class Helper
{
    public static function uuidToBase64($uuid, $length): string
    {
        return base64_encode(substr($uuid, 0, $length));
    }

    public static function getServerKey($timestamp, $length): string
    {
        return base64_encode(substr(md5($timestamp), 0, $length));
    }

    /**
     * Returns a GUIDv4 string.
     *
     * Uses the best cryptographically secure method
     * for all supported platforms with fallback to an older,
     * less secure version.
     *
     * @param  bool  $format
     * @return string
     */
    public static function guid(bool $format = false): string
    {
        // Windows
        if (function_exists('com_create_guid') === true) {
            return md5(trim(com_create_guid(), '{}'));
        }

        // OSX/Linux
        if (function_exists('openssl_random_pseudo_bytes') === true) {
            $data = openssl_random_pseudo_bytes(16);
            $data[6] = chr(ord($data[6]) & 0x0F | 0x40);    // set version to 0100
            $data[8] = chr(ord($data[8]) & 0x3F | 0x80);    // set bits 6-7 to 10
            if ($format) {
                return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
            }

            return md5(vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4)).'-'.time());
        }
        // Fallback (PHP 4.2+)
        mt_srand((float) microtime() * 10000);
        $char_id = strtolower(md5(uniqid(rand(), true)));
        $hyphen = chr(45);                  // "-"

        return substr($char_id, 0, 8).$hyphen.
        substr($char_id, 8, 4).$hyphen.
        substr($char_id, 12, 4).$hyphen.
        substr($char_id, 16, 4).$hyphen.
        substr($char_id, 20, 12);
    }

    public static function generateOrderNo(): string
    {
        $randomChar = mt_rand(10000, 99999);

        return date('YmdHms').substr(microtime(), 2, 6).$randomChar;
    }

    public static function exchange($from, $to)
    {
        $result = file_get_contents('https://api.exchangerate.host/latest?symbols='.$to.'&base='.$from);
        $result = json_decode($result, true);

        return $result['rates'][$to];
    }

    public static function randomChar($len, $special = false): string
    {
        $chars = [
            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k',
            'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v',
            'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G',
            'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R',
            'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '0', '1', '2',
            '3', '4', '5', '6', '7', '8', '9',
        ];

        if ($special) {
            $chars = array_merge($chars, [
                '!', '@', '#', '$', '?', '|', '{', '/', ':', ';',
                '%', '^', '&', '*', '(', ')', '-', '_', '[', ']',
                '}', '<', '>', '~', '+', '=', ',', '.',
            ]);
        }

        $charsLen = count($chars) - 1;
        shuffle($chars);
        $str = '';
        for ($i = 0; $i < $len; $i++) {
            $str .= $chars[mt_rand(0, $charsLen)];
        }

        return $str;
    }

    public static function multiPasswordVerify($algo, $salt, $password, $hash): bool
    {
        return match ($algo) {
            'md5' => md5($password) === $hash,
            'sha256' => hash('sha256', $password) === $hash,
            'md5salt' => md5($password.$salt) === $hash,
            default => password_verify($password, $hash),
        };
    }

    public static function emailSuffixVerify($email, $suffixs): bool
    {
        $suffix = explode('@', $email)[1];
        if (! $suffix) {
            return false;
        }
        if (! is_array($suffixs)) {
            $suffixs = explode(',', $suffixs);
        }
        if (! in_array($suffix, $suffixs)) {
            return false;
        }

        return true;
    }

    public static function trafficConvert(int $byte): int|string
    {
        $kb = 1024;
        $mb = 1048576;
        $gb = 1073741824;
        if ($byte > $gb) {
            return round($byte / $gb, 2).' GB';
        } elseif ($byte > $mb) {
            return round($byte / $mb, 2).' MB';
        } elseif ($byte > $kb) {
            return round($byte / $kb, 2).' KB';
        } elseif ($byte < 0) {
            return 0;
        } else {
            return round($byte, 2).' B';
        }
    }

    public static function getSubscribeUrl($path): object|string
    {
        $subscribeUrls = explode(',', config('v2board.subscribe_url'));
        $subscribeUrl = $subscribeUrls[rand(0, count($subscribeUrls) - 1)];
        if ($subscribeUrl) {
            return $subscribeUrl.$path;
        }

        return url($path);
    }

    public static function randomPort($range): int
    {
        $portRange = explode('-', $range);

        return rand($portRange[0], $portRange[1]);
    }
}
