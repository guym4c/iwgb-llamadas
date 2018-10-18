<?php

namespace Action;

class NewCaller extends CampaignAction {

    public function __invoke(\Request $request, \Response $response, $args) {
        return $this->render($request,$response, 'newCaller.html.twig', ['campaign' => $args['campaign']]);
    }
}