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

        for ($i = 0; $i < count($answers); $i++) {
            $params[$answers[$i]['field']['ref']] = $answers[$i][$answers[$i]['type']];
        }

        if (empty($params['answered'])) $params['answered'] = 0;
        if (empty($params['recall'])) $params['recall'] = 1;
        if (empty($params['attending'])) $params['attending'] = 0;
        if (empty($params['notes'])) $params['notes'] = '';

        $callee = \Callee::constructFromId($this->db, $params['callee']);
        $call = \Call::constructCall($this->db,
            \Caller::constructFromId($this->db, $params['caller']),
            $callee,
            $params['answered'],
            $params['notes']);
        $call->save();

        $callee->recall = $params['recall'];
        $callee->attending = $params['attending'];
        $callee->save();

        return $response->withStatus(200);

    }
}