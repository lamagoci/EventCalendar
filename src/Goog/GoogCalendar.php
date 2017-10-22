<?php
declare(strict_types=1);

namespace EventCalendar\Goog;

use EventCalendar\BasicCalendar;

/**
 * Integration with events from Google Calendar
 *
 * Experimental
 *
 * @property-write GoogAdapter $googAdapter
 * @todo Allow mix of events from Google Calendar with custom events
 */
class GoogCalendar extends BasicCalendar
{
    /**
     * Show top navigation for changing months, default <b>true</b>
     */

    /**
     * show link to event in Google Calendar, default is <b>true</b>
     */
    public const OPT_SHOW_EVENT_LINK = 'showEventLink';
    /**
     * show location if it is set, default is <b>true</b>
     */
    public const OPT_SHOW_EVENT_LOCATION = 'showEventLocation';
    /**
     * show description of event if it is set, default is <b>true</b>
     */
    public const OPT_SHOW_EVENT_DESCRIPTION = 'showEventDescription';
    /**
     * show start date of event, default is <b>true</b>
     */
    public const OPT_SHOW_EVENT_START = 'showEventStart';
    /**
     * show end date of event, default is <b>true</b>
     */
    public const OPT_SHOW_EVENT_END = 'showEventEnd';
    /**
     * Datetime format for start and end date, default is <b>F j, Y, g:i a</b>
     */
    public const OPT_EVENT_DATEFORMAT = 'eventDateformat';

    /**
     * @var GoogAdapter
     */
    private $googAdapter;
    
    public function __construct()
    {
        parent::__construct();
        $this->setOptions([
            'showEventLink' => true,
            'showEventLocation' => true,
            'showEventDescription' => true,
            'showEventStart' => true,
            'showEventEnd' => true,
            'eventDateformat' => 'F j, Y, g:i a',
        ]);
    }
    
    protected function getTemplateFile(): string
    {
        return __DIR__ . '/GoogCalendar.latte';
    }
    
    public function setGoogAdapter(GoogAdapter $googAdapter)
    {
        $this->googAdapter = $googAdapter;
    }
    
    /**
     * Do not set events directly, use GoogAdapter. Mix of events from Google with customs events is not implemented yet.
     * @throws \LogicException
     */
    public function setEvents(\EventCalendar\IEventModel $events)
    {
        throw new \LogicException('Do not set events directly, use GoogAdapter.');
    }

    /**
     * @throws \EventCalendar\Goog\GoogApiException
     */
    public function render(): void
    {
        $this->prepareDate();
        /** @var int $year */
        $year = $this->year;
        /** @var int $month */
        $month = $this->month;
        $this->googAdapter->setBoundary($year, $month);
        try {
            $this->events = $this->googAdapter->loadEvents();
        } catch (GoogApiException $e) {
            throw $e;
        }
        parent::render();
    }
}
