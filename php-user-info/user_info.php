<?php

function get_ip() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
    }
}

function get_user_browser() {
    $agent = $_SERVER['HTTP_USER_AGENT'] ?? '';

    if (strpos($agent, 'Edg') !== false) return 'Microsoft Edge';
    if (strpos($agent, 'Chrome') !== false) return 'Google Chrome';
    if (strpos($agent, 'Firefox') !== false) return 'Mozilla Firefox';
    if (strpos($agent, 'Safari') !== false) return 'Safari';
    if (strpos($agent, 'MSIE') !== false || strpos($agent, 'Trident') !== false) {
        return 'Internet Explorer';
    }

    return 'Unknown Browser';
}


function get_os() {
    $agent = $_SERVER['HTTP_USER_AGENT'] ?? '';

    if (strpos($agent, 'Windows') !== false) return 'Windows';
    if (strpos($agent, 'Mac') !== false) return 'Mac OS';
    if (strpos($agent, 'Linux') !== false) return 'Linux';
    if (strpos($agent, 'Android') !== false) return 'Android';
    if (strpos($agent, 'iPhone') !== false) return 'iOS';

    return 'Unknown OS';
}

function get_device() {
    $agent = $_SERVER['HTTP_USER_AGENT'] ?? '';

    if (preg_match('/mobile/i', $agent)) return 'Mobile';
    if (preg_match('/tablet/i', $agent)) return 'Tablet';

    return 'Computer';
}

function get_location(string $ip): ?array
{
    if ($ip === '127.0.0.1' || $ip === '::1') {
        return null;
    }

    $json = @file_get_contents("https://ipapi.co/{$ip}/json/");
    if (!$json) {
        return null;
    }

    $data = json_decode($json, true);
    if (!is_array($data) || !empty($data['error'])) {
        return null;
    }

    return [
        'country'   => $data['country_name'] ?? null,
        'region'    => $data['region'] ?? null,
        'city'      => $data['city'] ?? null,
        'latitude'  => $data['latitude'] ?? null,
        'longitude' => $data['longitude'] ?? null,
    ];
}
