<?php
// app/helpers/chat_helper.php

if (!function_exists('chatLastActivityDiff')) {
    function chatLastActivityDiff($datetime)
    {
        if (!$datetime) return null;
        return time() - strtotime($datetime);
    }
}

/**
 * Text status (Online / x minutes ago / x hours ago)
 */
if (!function_exists('chatStatusText')) {
    function chatStatusText($datetime)
    {
        if (!$datetime) return 'Offline';

        $diff = chatLastActivityDiff($datetime);
        if ($diff === null) return 'Offline';

        if ($diff < 60) return 'Online';
        if ($diff < 3600) return floor($diff / 60) . ' minutes ago';
        if ($diff < 86400) return floor($diff / 3600) . ' hours ago';

        return date('d M Y H:i', strtotime($datetime));
    }
}

/**
 * Status code: online | away | offline
 */
if (!function_exists('chatStatusCode')) {
    function chatStatusCode($datetime)
    {
        if (!$datetime) return 'offline';

        $diff = chatLastActivityDiff($datetime);

        if ($diff < 60) return 'online';
        if ($diff < 300) return 'away'; // 5 menit
        return 'offline';
    }
}

/**
 * Warna status (bootstrap-friendly)
 */
if (!function_exists('chatStatusColor')) {
    function chatStatusColor($datetime)
    {
        $status = chatStatusCode($datetime);

        return match ($status) {
            'online' => 'text-success',
            'away'   => 'text-warning',
            default  => 'text-muted',
        };
    }
}

/**
 * Badge bulat kecil (online indicator)
 */
if (!function_exists('chatOnlineBadge')) {
    function chatOnlineBadge($datetime)
    {
        $status = chatStatusCode($datetime);

        $color = match ($status) {
            'online' => '#28a745', // hijau
            'away'   => '#ffc107', // kuning
            default  => '#adb5bd', // abu
        };

        return '<span style="
            display:inline-block;
            width:8px;
            height:8px;
            border-radius:50%;
            background-color:' . $color . ';
            margin-right:6px;
        "></span>';
    }
}

/**
 * Full HTML status (badge + text)
 */
if (!function_exists('chatStatusHtml')) {
    function chatStatusHtml($datetime)
    {
        return chatOnlineBadge($datetime) .
            '<span class="' . chatStatusColor($datetime) . ' small">'
            . chatStatusText($datetime) .
            '</span>';
    }
}
