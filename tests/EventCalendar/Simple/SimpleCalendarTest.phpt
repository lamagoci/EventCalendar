<?php
declare(strict_types=1);

namespace EventCalendar\Simple;

require __DIR__ . '/../../bootstrap.php';

use Tester\DomQuery;
use Tester\Assert;

class SimpleCalendarTest extends \Tester\TestCase
{
    use \Testbench\TComponent;
    
    /**
     * @var SimpleCalendar
     */
    private $calendar;
    
    protected function setUp()
    {
        if (is_null($this->calendar)) {
            $this->calendar = new SimpleCalendar();
        }
        $this->attachToPresenter($this->calendar);
    }
    
    public function testBasic()
    {
        $html = $this->renderAndReturnHtml();
        $dom = DomQuery::fromHtml($html);
        Assert::true($dom->has('.ec-monthTable'));
    }
    
    /**
     * Check if the month name is called January
     */
    public function testEnglishMonthName()
    {
        $this->calendar->year = 2013;
        $this->calendar->month = 1;
        $html = $this->renderAndReturnHtml();
        $dom = DomQuery::fromHtml($html);
        $elem = $dom->find('caption');
        Assert::contains('January', $elem[0]->asXML());
    }
    
    public function testWrongLang()
    {
        $this->calendar->setLanguage('esperanto');
        Assert::exception(function () {
            $this->calendar->render();
        }, \LogicException::class);
    }
    
    /**
     * Check if the first day of week is called "Pondělí"
     */
    public function testCzechCalendar()
    {
        $this->calendar->setFirstDay(SimpleCalendar::FIRST_MONDAY);
        $this->calendar->setLanguage(SimpleCalendar::LANG_CZ);
        $html = $this->renderAndReturnHtml();
        $dom = DomQuery::fromHtml($html);
        $elem = $dom->find('.ec-monthTable th');
        $mondayName = $elem[0]->asXML();
        Assert::same('Pondělí', utf8_decode(strip_tags($mondayName)));
    }
    
    public function testDisabledBottomNav()
    {
        $this->calendar->setOptions([SimpleCalendar::OPT_SHOW_BOTTOM_NAV => false]);
        $html = $this->renderAndReturnHtml();
        $dom = DomQuery::fromHtml($html);
        Assert::true(!$dom->has('.ec-bottomNavigation'));
    }
    
    /**
     * Check if the german calendar starting on Monday has wday truncated to three chars
     */
    public function testMaxLenOfWday()
    {
        $this->calendar->setLanguage(SimpleCalendar::LANG_DE);
        $this->calendar->setFirstDay(SimpleCalendar::FIRST_MONDAY);
        $this->calendar->setOptions([SimpleCalendar::OPT_WDAY_MAX_LEN => 3]);
        $html = $this->renderAndReturnHtml();
        $dom = DomQuery::fromHtml($html);
        $wednesElem = $dom->find('.ec-monthTable th');
        $wednesdayName = $wednesElem[2]->asXML();
        Assert::same('Mit', strip_tags($wednesdayName));
    }
    
    public function testEvent()
    {
        $this->calendar->year = 2012;
        $this->calendar->month = 2;
        $this->calendar->setEvents(new TestEvent());
        $html = $this->renderAndReturnHtml();
        $dom = DomQuery::fromHtml($html);
        $noOfEvents = count($dom->find('.ec-event'));
        Assert::equal(2, $noOfEvents);
    }
    
    public function testEventPosition()
    {
        $this->calendar->year = 2012;
        $this->calendar->month = 2;
        $this->calendar->setEvents(new TestEvent());
        $html = $this->renderAndReturnHtml();
        $dom = DomQuery::fromHtml($html);
        $dayElems = $dom->find('.ec-eventDay .ec-dayOfEvents');
        $day = (int) strip_tags($dayElems[0]->asXML());
        Assert::true($dom->has('.ec-eventDay .ec-eventBox') && $day === 2);
    }
    
    private function renderAndReturnHtml(): string
    {
        ob_start();
        $this->calendar->render();
        $html = ob_get_clean();
        return $html;
    }
}

$testCase = new SimpleCalendarTest();
$testCase->run();
