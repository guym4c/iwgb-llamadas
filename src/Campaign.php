<?php

/**
 * Class Campaign
 *
 * @property Database $db
 */
class Campaign {

    public $name;
    public $ends;
    public $script;

    public $db;

    public function __construct() {}

    public function initialise(Database $db) {
        $this->db = $db;
    }

    public function constructFromName(Database $db, $name) {
        $campaign = $db->getCampaign($name);
        $campaign->initialise($db);
        return $campaign;
    }
}