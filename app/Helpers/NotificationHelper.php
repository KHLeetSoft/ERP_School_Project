<?php

namespace App\Helpers;

class NotificationHelper
{
    public static function getNotificationIcon($type)
    {
        $icons = [
            'assignment' => 'book',
            'exam' => 'clipboard-check',
            'library' => 'book-open',
            'hostel' => 'home',
            'fees' => 'credit-card',
            'transport' => 'bus',
            'general' => 'info-circle',
            'urgent' => 'exclamation-triangle',
        ];
        return $icons[$type] ?? 'bell';
    }

    public static function getNotificationColor($type)
    {
        $colors = [
            'assignment' => 'primary',
            'exam' => 'danger',
            'library' => 'info',
            'hostel' => 'success',
            'fees' => 'warning',
            'transport' => 'secondary',
            'general' => 'muted',
            'urgent' => 'danger',
        ];
        return $colors[$type] ?? 'primary';
    }

    public static function getPriorityColor($priority)
    {
        $colors = [
            'low' => 'secondary',
            'medium' => 'info',
            'high' => 'warning',
            'urgent' => 'danger',
        ];
        return $colors[$priority] ?? 'secondary';
    }

    public static function getPriorityAlertClass($priority)
    {
        $classes = [
            'low' => 'secondary',
            'medium' => 'info',
            'high' => 'warning',
            'urgent' => 'danger',
        ];
        return $classes[$priority] ?? 'secondary';
    }

    public static function getEventIcon($type)
    {
        $icons = [
            'exam' => 'clipboard-check',
            'library' => 'book-open',
            'assignment' => 'book',
            'general' => 'calendar',
        ];
        return $icons[$type] ?? 'calendar';
    }

    public static function getEventColor($type)
    {
        $colors = [
            'exam' => 'danger',
            'library' => 'info',
            'assignment' => 'primary',
            'general' => 'success',
        ];
        return $colors[$type] ?? 'primary';
    }
}
