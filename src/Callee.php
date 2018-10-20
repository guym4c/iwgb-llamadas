<?php

/**
 * Class Callee
 *
 * @property Database $db
 */
class Callee {

    public $id;
    public $number;
    public $name;
    public $company;
    public $campaign;
    public $recall;
    public $attending;

    public $db;

    public function __construct() {}

    public function initialise(Database $db) {
        $this->db = $db;
    }

    public static function constructFromId(Database $db, $id) {
        $callee = $db->getCallee($id);
        $callee->initialise($db);
        return $callee;
    }

    public function save() {
        $this->db->save('callees', [
            'id'        => $this->id,
            'number'    => $this->number,
            'name'      => $this->name,
            'company'   => $this->company,
            'campaign'  => $this->campaign,
            'recall'    => $this->recall,
            'attending' => $this->attending,
        ], 'id');
    }
}