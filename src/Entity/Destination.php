<?php

class Destination
{
    public $id;
    public $countryName;
    public $conjunction;
    /**
     * @deprecated if not used in the project then deleted.
     */
    public $name;
    public $computerName;

    public function __construct($id, $countryName, $conjunction, $computerName)
    {
        $this->id = $id;
        $this->countryName = $countryName;
        $this->conjunction = $conjunction;
        $this->computerName = $computerName;
    }
}
