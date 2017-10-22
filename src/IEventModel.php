<?php
declare(strict_types=1);

namespace EventCalendar;

/**
 * <i>Tip:</i> For max performance you can load events only for current month. If user changes month, handle this by event onDateChange.
 */
interface IEventModel
{

    /**
     * exists some event for that day?
     */
    public function isForDate(int $year, int $month, int $day): bool;

    /**
     * return array with events - output is NOT escaped (you can use html)
     */
    public function getForDate(int $year, int $month, int $day): array;
}
