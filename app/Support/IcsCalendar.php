<?php

namespace App\Support;

use Carbon\Carbon;

/** Builds a minimal single-event .ics file — no external package needed for just two use cases (milestone due dates, consultation times). */
class IcsCalendar
{
    /**
     * @param  string  $uid  Stable unique id for this event (e.g. "milestone-42@visionbridgesolutions.com")
     * @param  bool  $allDay  Milestones only have a date, not a time — rendered as a full-day event
     */
    public static function event(string $uid, string $title, ?string $description, Carbon $start, bool $allDay = false, ?string $location = null): string
    {
        $now = now()->utc()->format('Ymd\THis\Z');

        if ($allDay) {
            $dtStart = 'DTSTART;VALUE=DATE:'.$start->format('Ymd');
            $dtEnd = 'DTEND;VALUE=DATE:'.$start->copy()->addDay()->format('Ymd');
        } else {
            $dtStart = 'DTSTART:'.$start->copy()->utc()->format('Ymd\THis\Z');
            $dtEnd = 'DTEND:'.$start->copy()->utc()->addHour()->format('Ymd\THis\Z');
        }

        $lines = [
            'BEGIN:VCALENDAR',
            'VERSION:2.0',
            'PRODID:-//VisionBridge Solutions//Client Portal//EN',
            'CALSCALE:GREGORIAN',
            'BEGIN:VEVENT',
            'UID:'.$uid,
            'DTSTAMP:'.$now,
            $dtStart,
            $dtEnd,
            'SUMMARY:'.self::escape($title),
        ];

        if ($description) {
            $lines[] = 'DESCRIPTION:'.self::escape($description);
        }

        if ($location) {
            $lines[] = 'LOCATION:'.self::escape($location);
        }

        $lines[] = 'END:VEVENT';
        $lines[] = 'END:VCALENDAR';

        // .ics requires CRLF line endings.
        return implode("\r\n", $lines)."\r\n";
    }

    private static function escape(string $text): string
    {
        return str_replace(["\\", "\n", ",", ";"], ["\\\\", '\\n', '\,', '\;'], $text);
    }
}
