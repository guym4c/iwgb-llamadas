<?php

namespace Action;


class LogIn extends CampaignAction {

    public function __invoke($request, $response, $args) {
        return $this->render($request, $response, 'logIn.html.twig', [
            'callers'   => $this->db->getCallers($args['campaign']),
            'campaign' => $args['campaign']
        ]);
    }
}