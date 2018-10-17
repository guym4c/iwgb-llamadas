<?php

namespace Action;

class NewCaller extends CampaignAction {

    public function __invoke($request, $response, $args) {
        return $this->render($request,$response, 'newCaller.html.twig', ['campaign' => $args['campaign']]);
    }
}