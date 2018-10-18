<?php

namespace Action;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class NewCaller extends CampaignAction {

    public function __invoke(Request $request, Response $response, $args) {
        return $this->render($request,$response, 'newCaller.html.twig', ['campaign' => $args['campaign']]);
    }
}