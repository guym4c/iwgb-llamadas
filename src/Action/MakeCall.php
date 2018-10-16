<?php

namespace Action;


class MakeCall extends CampaignAction {

    public function __invoke($request, $response, $args) {
        return $this->render($request, $response, 'call.html.twig', [
            'callee'    => $this->db->getNextCallee($args['campaign']),
            'campaign'  => $args['campaign'],
            'caller'    => $this->db->getCaller($args['caller']),
            'script'    => $this->db->getCampaign($args['campaign'])->script,
        ]);
    }
}