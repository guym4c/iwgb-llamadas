<?php

namespace Action;

class Callback extends GenericAction {

    public function __invoke($request, $response, $args) {
        $json = $request->getParsedBody();
        $error = json_last_error_msg();
        if ($json['event_type'] != 'form_response') return $this->notFound($request, $response);
        $params = $json['form_response']['hidden'];
        $answers = $json['form_response']['answers'];
        $questions = ['answered', 'recall', 'notes'];

        for ($i = 0; $i < count($answers); $i++) {
            $params[$questions[$i]] = $answers[$i][$answers[$i]['type']];
        }
        print_r($answers);
        print_r($params);

        $callee = \Callee::constructFromId($this->db, $params['callee']);
        $call = \Call::constructCall($this->db,
            \Caller::constructFromId($this->db, $params['caller']),
            $callee,
            $params['answered'],
            $params['notes']);
        $call->save();

        $callee->recall = $params['recall'];
        $callee->save();


    }
}