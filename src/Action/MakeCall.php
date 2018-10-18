<?php

namespace Action;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class MakeCall extends CampaignAction {

    public function __invoke(Request $request, Response $response, $args) {
        $callee = $this->db->getNextCallee($args['campaign']);
        return $this->render($request, $response, 'call.html.twig', [
            'callee'    => $callee,
            'campaign'  => $args['campaign'],
            'caller'    => $this->db->getCaller($args['caller']),
            'script'    => $this->db->getCampaign($args['campaign'])->script,
            'calls'     => $this->db->getCalls($callee),
        ]);
    }
}