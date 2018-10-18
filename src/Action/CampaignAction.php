<?php

namespace Action;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

abstract class CampaignAction extends GenericAction {

    public function render(Request $request, Response $response, $template, $vars) {
        if ($this->db->exists('campaigns', 'name', $vars['campaign'])) {
            return parent::render($request, $response, $template, $vars);
        } else {
            return $this->notFound($request, $response);
        }
    }
}