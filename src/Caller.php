<?php

/**
 * Class Caller
 * @property string $email
 * @property string $name
 * @property string $campaign
 * @property Database $db
 *
 */
class Caller {

    public $id;
    public $email;
    public $name;
    public $campaign;

    public $db;

    public function __construct() {}

    public function initialise(Database $db) {
        $this->db = $db;
    }

    public static function constructFromId(Database $db, $id) {
        $caller = $db->getCaller($id);
        $caller->initialise($db);
        return $caller;
    }

}