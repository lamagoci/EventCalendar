<?php
declare(strict_types=1);

namespace EventCalendar\Simple;

use EventCalendar\IEventModel;

class TestEvent implements IEventModel
{
    
    private $events = [];
    
    public function __construct()
    {
        $this->events['2012-02-02'] = ['Custom event with **bold** text', 'Another event'];
    }
    
    public function getForDate(int $year, int $month, int $day): array
    {
        return $this->events[$this->formatDate($year, $month, $day)];
    }
    
    public function isForDate(int $year, int $month, int $day): bool
    {
        return array_key_exists($this->formatDate($year, $month, $day), $this->events);
    }
    
    private function formatDate(int $year, int $month, int $day): string
    {
        return sprintf('%d-%02d-%02d', $year, $month, $day);
    }
}
