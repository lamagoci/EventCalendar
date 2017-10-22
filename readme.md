EventCalendar
============

[![Total Downloads](https://poser.pugx.org/konecnyjakub/event-calendar/downloads)](https://packagist.org/packages/konecnyjakub/event-calendar)  [![Latest Stable Version](https://poser.pugx.org/konecnyjakub/event-calendar/v/stable)](https://packagist.org/packages/konecnyjakub/event-calendar) [![Latest Unstable Version](https://poser.pugx.org/konecnyjakub/event-calendar/v/unstable)](https://packagist.org/packages/konecnyjakub/event-calendar) [![Build Status](https://travis-ci.org/konecnyjakub/EventCalendar.svg?branch=master)](https://travis-ci.org/konecnyjakub/EventCalendar) ![License](https://poser.pugx.org/konecnyjakub/event-calendar/license)

This is an add-on component for Nette framework which enables displaying various events in calendar. It provides methods for localisation & customization. You can also use html and Texy! in your event texts.

Installation
============

The best way to install it is via Composer. Just add **konecnyjakub/event-calendar** to your dependencies.

Quick start
============

Add to your code (in presenter/control):

```php
    protected function createComponentCalendar() {
        $cal = new EventCalendar\Simple\SimpleCalendar();
        return $cal;
    }
```

and in template:

```latte
    {control calendar}
```
