<?php

namespace Action;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class Callback extends GenericAction {

    public function __invoke(Request $request, Response $response, $args) {
        $json = $request->getParsedBody();
        if ($json['event_type'] != 'form_response') return $this->notFound($request, $response);
        $params = $json['form_response']['hidden'];
        $answers = $json['form_response']['answers'];
        $questions = ['answered', 'recall', 'notes'];

        for ($i = 0; $i < count($answers); $i++) {
            $params[$questions[$i]] = $answers[$i][$answers[$i]['type']];
        }

        $callee = \Callee::constructFromId($this->db, $params['callee']);
        $call = \Call::constructCall($this->db,
            \Caller::constructFromId($this->db, $params['caller']),
            $callee,
            $params['answered'],
            $params['notes']);
        $call->save();

        $callee->recall = $params['recall'];
        $callee->save();

        return $response->withStatus(200);

    }
}