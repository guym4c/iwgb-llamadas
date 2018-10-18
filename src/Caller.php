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

    public static function constructCaller(Database $db, $email, $name, $campaign) {
        $caller = new Caller();
        $caller->db = $db;
        $caller->id = uniqid();
        $caller->email = $email;
        $caller->name = $name;
        $caller->campaign = $campaign;
        return $caller;
    }

    public function save() {
        $this->db->save('callers', [
            'id'        => $this->id,
            'email'     => $this->email,
            'name'      => $this->name,
            'campaign'  => $this->campaign,
        ], 'id');
    }

}