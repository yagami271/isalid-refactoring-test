<?php

class Quote
{
    public int $id;
    public int $siteId;
    public int $destinationId;
    public \DateTime $dateTime;

    public function __construct(int $id, int $siteId, int $destinationId, \DateTime $dateTime)
    {
        $this->id = $id;
        $this->siteId = $siteId;
        $this->destinationId = $destinationId;
        $this->dateTime = $dateTime;
    }

    /**
     * @deprecated use $this->getIdAsHtml().
     * static method is used when we don't want to create instance of the class but here the Quote is already created.
     */
    public static function renderHtml(Quote $quote)
    {
        return '<p>' . $quote->id . '</p>';
    }

    /**
     * @deprecated use $this->getIdAsString().
     */
    public static function renderText(Quote $quote)
    {
        return (string)$quote->id;
    }

    public function getIdAsHtml(): string
    {
        return '<p>' . $this->id . '</p>';
    }

    public function getIdAsString(): string
    {
        return (string)$this->id;
    }
}