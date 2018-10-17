<?php

namespace Action;


class SelectUser extends CampaignAction {

    public function __invoke($request, $response, $args) {
        return $this->render($request, $response, 'selectUser.html.twig', [
            'callers'   => $this->db->getCallers($args['campaign']),
            'campaign' => $args['campaign']
        ]);
    }
}