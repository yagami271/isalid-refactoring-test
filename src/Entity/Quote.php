<?php

class Quote
{
    public $id;
    public $siteId;
    public $destinationId;
    public $dateQuoted;

    public function __construct($id, $siteId, $destinationId, $dateQuoted)
    {
        $this->id = $id;
        $this->siteId = $siteId;
        $this->destinationId = $destinationId;
        $this->dateQuoted = $dateQuoted;
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