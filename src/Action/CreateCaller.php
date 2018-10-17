<?php

namespace Action;

class CreateCaller extends CampaignAction {

    public function __invoke($request, $response, $args) {
        $post = $request->getParsedBody();
        if (empty($post['name']) || empty($post['email']) || empty($post['agree'])) {
            return $this->notFound($request, $response);
        }
        \Caller::constructCaller($this->db, $post['email'], $post['name'], $args['campaign'])->save();
        return $response->withRedirect('/call/' . $args['campaign']);

    }
}