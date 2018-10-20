<?php

/**
 * Class Call
 * @property Database $db
 */
class Call {

    public $id;
    public $caller;
    public $callee;
    public $timestamp;
    public $answered;
    public $notes;

    public $db;

    public function __construct() {}

    public function initialise(Database $db) {
        $this->db = $db;
    }

    /**
     * @param Database $db
     * @param Caller $caller
     * @param Callee $callee
     * @param bool $answered
     * @param string $notes
     * @return Call
     */
    public static function constructCall(Database $db, Caller $caller, Callee $callee, $answered, $notes) {
        $call = new Call();
        $call->db = $db;
        $call->id = uniqid();
        $call->caller = $caller->id;
        $call->callee = $callee->id;
        $call->answered = $answered;
        $call->notes = $notes;
        return $call;
    }

    public function save() {

        $this->db->save('calls', [
                'id'        => $this->id,
                'caller'    => $this->caller,
                'callee'    => $this->callee,
                'answered'  => $this->answered,
                'notes'     => $this->notes,
            ], 'id'
        );
    }



}