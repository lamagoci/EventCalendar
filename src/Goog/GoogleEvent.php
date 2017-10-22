<?php
declare(strict_types=1);

namespace EventCalendar\Goog;

/**
 * Represent single event from Google Calendar
 *
 * @property-read string $id
 * @property string $status
 * @property string $htmlLink
 * @property \DateTime $created
 * @property \DateTime $updated
 * @property string $summary
 * @property string|NULL $location
 * @property string|NULL $description
 * @property-read string $creator
 * @property \DateTime $start
 * @property \DateTime $end
 */
class GoogleEvent
{
    use \Nette\SmartObject;
    
    /**
     * @var string
     */
    private $id;
    /**
     * @var string
     */
    private $status;
    /**
     * @var string
     */
    private $htmlLink;
    /**
     * @var \DateTime
     */
    private $created;
    /**
     * @var \DateTime
     */
    private $updated;
    /**
     * @var string
     */
    private $summary;
    /**
     * @var string|NULL
     */
    private $location = null;
    /**
     * @var string|NULL
     */
    private $description = null;
    private $creator;
    /**
     * @var \DateTime
     */
    private $start;
    /**
     * @var \DateTime
     */
    private $end;
    
    public function __construct(string $id)
    {
        $this->id = $id;
    }
    
    public function getId(): string
    {
        return $this->id;
    }
    
    public function getStatus(): string
    {
        return $this->status;
    }
    
    public function getHtmlLink(): string
    {
        return $this->htmlLink;
    }
    
    public function getCreated(): \DateTime
    {
        return $this->created;
    }
    
    public function getUpdated(): \DateTime
    {
        return $this->updated;
    }
    
    public function getSummary(): string
    {
        return $this->summary;
    }
    
    public function getLocation(): ?string
    {
        return $this->location;
    }
    
    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getCreator()
    {
        return $this->creator;
    }
    
    public function getStart(): \DateTime
    {
        return $this->start;
    }
    
    public function getEnd(): \DateTime
    {
        return $this->end;
    }
    
    public function setStatus($status): GoogleEvent
    {
        $this->status = $status;
        return $this;
    }
    
    public function setHtmlLink(string $htmlLink): GoogleEvent
    {
        $this->htmlLink = $htmlLink;
        return $this;
    }

    /**
     * @param string|\DateTime $created
     */
    public function setCreated($created): GoogleEvent
    {
        if (!$created instanceof \DateTime) {
            $created = new \DateTime($created);
        }
        $this->created = $created;
        return $this;
    }

    /**
     * @param string|\DateTime $updated
     */
    public function setUpdated($updated): GoogleEvent
    {
        if (!$updated instanceof \DateTime) {
            $updated = new \DateTime($updated);
        }
        $this->updated = $updated;
        return $this;
    }
    
    public function setSummary(string $summary): GoogleEvent
    {
        $this->summary = $summary;
        return $this;
    }
    
    public function setLocation(string $location): GoogleEvent
    {
        $this->location = $location;
        return $this;
    }
    
    public function setDescription(string $description): GoogleEvent
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param string|\DateTime $start
     */
    public function setStart($start): GoogleEvent
    {
        if (!($start instanceof \DateTime)) {
            $start = new \DateTime($start);
        }
        $this->start = $start;
        return $this;
    }

    /**
     * @param string|\DateTime $end
     */
    public function setEnd($end): GoogleEvent
    {
        if (!$end instanceof \DateTime) {
            $end = new \DateTime($end);
        }
        $this->end = $end;
        return $this;
    }
}
