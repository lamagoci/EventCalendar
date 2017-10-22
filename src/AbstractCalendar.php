<?php
declare(strict_types=1);

namespace EventCalendar;

use Nette\Application\UI;
use Nette\Utils\Strings;

/**
 * @property-write int $firstDay
 * @property-write array $options
 * @property-write IEventModel $events
 * @property-read \Nette\Bridges\ApplicationLatte\Template $template
 * @method void onDateChange(int $year, int $month)
 */
abstract class AbstractCalendar extends UI\Control
{
    
    public const FIRST_SUNDAY = 0, FIRST_MONDAY = 1;
    
    /**
     * Show top navigation for changing months, default <b>true</b>
     */
    public const OPT_SHOW_TOP_NAV = 'showTopNav';
    /**
     * Show bottom navigation for changing months, default <b>true</b>
     */
    public const OPT_SHOW_BOTTOM_NAV = 'showBottomNav';
    /**
     * maximum length of wday names, by default, full name is used (<b>null</b>)
     */
    public const OPT_WDAY_MAX_LEN = 'wdayMaxLen';

    /**
     * @var int|NULL
     * @persistent
     */
    public $year = NULL;

    /**
     * @var int|NULL
     * @persistent
     */
    public $month = NULL;

    /**
     * @var callable[]
     */
    public $onDateChange;
    
    /**
     * @var int
     */
    protected $firstDay = self::FIRST_SUNDAY;

    /**
     * @var IEventModel
     */
    protected $events;
    
    /**
     * @var array default options for calendar - you can change defaults by setOptions()
     */
    protected $options = [
        'showTopNav' => true,
        'showBottomNav' => true,
        'wdayMaxLen' => NULL,
    ];
    
    abstract protected function getTemplateFile(): string;

    /**
     * Specify the date on which the week starts
     */
    public function setFirstDay(int $day)
    {
        $this->firstDay = $day;
    }

    /**
     * Changes default options, see OPT constants for currently supported options for each type of calendar
     */
    public function setOptions(array $options)
    {
        foreach ($options as $key => $value) {
            $this->options[$key] = $value;
        }
    }

    public function setEvents(IEventModel $events)
    {
        $this->events = $events;
    }

    /** changes current month and invokes onDateChange event */
    public function handleChangeMonth(): void
    {
        $this->onDateChange($this->year, $this->month);
        if ($this->presenter->isAjax()) {
            $this->redrawControl('ecCalendar');
        } else {
            $this->redirect('this');
        }
    }
    
    public function render(): void
    {
        $this->template->setFile($this->getTemplateFile());

        $this->prepareDate();
    
        /** @var int $year */
        $year = $this->year;
        /** @var int $month */
        $month = $this->month;
        $dateInfo = [];
        $dateInfo['year'] = $year; // current year
        $dateInfo['month'] = $month; // current month
        $dateInfo['noOfDays'] = cal_days_in_month(CAL_GREGORIAN, $month, $year); // count of days in month
        $dateInfo['firstDayInMonth'] = $this->getFirstDayInMonth($year, $month); // first day of month

        $this->template->dateInfo = $dateInfo;
        $this->template->next = $this->getNextMonth($year, $month);
        $this->template->prev = $this->getPrevMonth($year, $month);
        $this->template->options = $this->options;
        $this->template->events = $this->events;

        $this->template->render();
    }
    
    protected function getFirstDayInMonth(int $year, int $month): int
    {
        $day = getdate(mktime(0, 0, 0, $month, 1, $year));
        if ($this->firstDay == self::FIRST_SUNDAY) {
            return $day['wday'];
        } else {
            if ($day['wday'] == 0) {
                return 6;
            } else {
                return $day['wday'] - 1;
            }
        }
    }
    
    protected function getNextMonth(int $year, int $month): array
    {
        $next = [];
        if ($month == 12) {
            $next['month'] = 1;
            $next['year'] = $year + 1;
        } else {
            $next['month'] = $month + 1;
            $next['year'] = $year;
        }
        return $next;
    }
    
    protected function getPrevMonth(int $year, int $month): array
    {
        $prev = [];
        if ($month == 1) {
            $prev['month'] = 12;
            $prev['year'] = $year - 1;
        } else {
            $prev['month'] = $month - 1;
            $prev['year'] = $year;
        }
        return $prev;
    }
    
    protected function truncateWdays(array $wdays): array
    {
        if ($this->options['wdayMaxLen'] > 0) {
            foreach ($wdays as &$value) {
                $value = Strings::substring($value, 0, $this->options['wdayMaxLen']);
            }
        }
        return $wdays;
    }
    
    protected function prepareDate(): void
    {
        if ($this->month === NULL || $this->year === NULL) {
            $today = getdate();
            $this->month = $today['mon'];
            $this->year = $today['year'];
        }
    }
}
