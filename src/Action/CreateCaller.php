<?php

namespace Action;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class CreateCaller extends CampaignAction {

    const ADMIN_EMAIL_SUBJECT = 'New IWGB Llamadas User';
    const NOTIFICATION_EMAIL_SUBJECT = 'Welcome to IWGB Llamadas';

    public function __invoke(Request $request, Response $response, $args) {
        $post = $request->getParsedBody();
        if (empty($post['name']) || empty($post['email']) || empty($post['agree'])) {
            return $this->notFound($request, $response);
        }
        \Caller::constructCaller($this->db, $post['email'], $post['name'], $args['campaign'])->save();
        (new \Email(\Keys::Admin,
            self::ADMIN_EMAIL_SUBJECT,
            "New IWGB Llamadas user has agreed to the Data Protection Statement.\n\n
            Name: " . $post['name'] . "\n
            Email: " . $post['email'] . "\n
            Campaign: " . $args['campaign']
        ))->send();
        ( new \Email($post['email'],
            self::NOTIFICATION_EMAIL_SUBJECT,
            'Welcome to IWGB Llamadas, ' . $post['name'] . "!\n\n
            You're on the " . $args['campaign'] . " project - you can log in here:\n
            https://llamadas.iwgb.org.uk/call/" . $args['campaign'] . "\n
            To get your campaign password, speak to your IWGB Rep."
        ))->send();
        return $response->withRedirect('/call/' . $args['campaign']);

    }
}