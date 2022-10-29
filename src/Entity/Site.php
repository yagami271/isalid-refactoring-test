<?php

class Site
{
    public int $id;
    public string $url;

    public function __construct(int $id, string $url)
    {
        $this->id = $id;
        $this->url = $url;
    }
}
