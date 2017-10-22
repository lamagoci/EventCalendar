<?php
declare(strict_types=1);

namespace EventCalendar;

/**
 * @property-write \Nette\Localization\ITranslator $translator
 */
abstract class BasicCalendar extends AbstractCalendar
{

    /**
     * @var \Nette\Localization\ITranslator
     */
    protected $translator;

    /**
     * set translator for calendar control
     */
    public function setTranslator(\Nette\Localization\ITranslator $translator)
    {
        $this->translator = $translator;
    }
    
    public function render(): void
    {
        $this->template->setTranslator($this->translator);
        $this->template->wdays = $this->getWdays();
        $this->template->monthNames = $this->getMonthNames();
        parent::render();
    }
    
    protected function getWdays(): array
    {
        $wdays = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        if ($this->firstDay == self::FIRST_MONDAY) {
            array_push($wdays, array_shift($wdays));
        }
        return $this->truncateWdays($wdays);
    }
    
    protected function getMonthNames(): array
    {
        $month = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        return $month;
    }
}
