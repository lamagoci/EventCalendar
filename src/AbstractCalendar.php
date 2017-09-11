<?php

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

    const FIRST_SUNDAY = 0, FIRST_MONDAY = 1;

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
     * array of callbacks fn(year, month)
     * @var array
     */
    public $onDateChange;
    protected $firstDay = self::FIRST_SUNDAY;

    /**
     * Model which implements ICalendarEvent
     * @var IEventModel
     */
    protected $events;
  
  /**
   * @var array
   */
    protected $options = [];

    abstract protected function getTemplateFile();

    /**
     * Specify the date on which the week starts
     * @param int $day
     */
    public function setFirstDay($day)
    {
        $this->firstDay = $day;
    }

    /**
     * Changes default options, see OPT constants for currently supported options for each type of calendar
     * 
     * @param array $options array of options
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
    public function handleChangeMonth()
    {
        $this->onDateChange($this->year, $this->month);
        if ($this->presenter->isAjax()) {
            $this->redrawControl('ecCalendar');
        } else {
            $this->redirect('this');
        }
    }

    public function render()
    {
        $this->template->setFile($this->getTemplateFile());

        $this->prepareDate();
    
        $dateInfo = [];
        $dateInfo['year'] = $this->year; // current year
        $dateInfo['month'] = $this->month; // current month
        $dateInfo['noOfDays'] = cal_days_in_month(CAL_GREGORIAN, $this->month, $this->year); // count of days in month
        $dateInfo['firstDayInMonth'] = $this->getFirstDayInMonth($this->year, $this->month); // first day of month

        $this->template->dateInfo = $dateInfo;
        $this->template->next = $this->getNextMonth($this->year, $this->month);
        $this->template->prev = $this->getPrevMonth($this->year, $this->month);
        $this->template->options = $this->options;
        $this->template->events = $this->events;

        $this->template->render();
    }

    protected function getFirstDayInMonth($year, $month)
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

    protected function getNextMonth($year, $month)
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

    protected function getPrevMonth($year, $month)
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

    protected function truncateWdays($wdays)
    {
        if ($this->options['wdayMaxLen'] > 0) {
            foreach ($wdays as &$value) {
                $value = Strings::substring($value, 0, $this->options['wdayMaxLen']);
            }
        }
        return $wdays;
    }

    protected function prepareDate()
    {
        if ($this->month === NULL || $this->year === NULL) {
            $today = getdate();
            $this->month = $today['mon'];
            $this->year = $today['year'];
        }
    }

}
