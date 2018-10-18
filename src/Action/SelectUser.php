<?php

namespace Action;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class SelectUser extends CampaignAction {

    public function __invoke(Request $request, Response $response, $args) {
        return $this->render($request, $response, 'selectUser.html.twig', [
            'callers'   => $this->db->getCallers($args['campaign']),
            'campaign' => $args['campaign']
        ]);
    }
}