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
