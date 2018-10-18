<?php

namespace Action;

class CreateCaller extends CampaignAction {

    const NOTIFICATION_EMAIL_SUBJECT = 'New IWGB Llamadas User';

    public function __invoke($request, $response, $args) {
        $post = $request->getParsedBody();
        if (empty($post['name']) || empty($post['email']) || empty($post['agree'])) {
            return $this->notFound($request, $response);
        }
        \Caller::constructCaller($this->db, $post['email'], $post['name'], $args['campaign'])->save();
        (new \Email(\Keys::Admin,
            self::NOTIFICATION_EMAIL_SUBJECT,
            "New IWGB Llamadas user has agreed to the Data Protection Statement.\n\n
            Name: " . $post['name'] . "\n
            Email: " . $post['email'] . "\n
            Campaign: " . $args['campaign']
        ))->send();
        return $response->withRedirect('/call/' . $args['campaign']);

    }
}