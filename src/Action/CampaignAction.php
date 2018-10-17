<?php

namespace Action;

abstract class CampaignAction extends GenericAction {

    public function render($request, $response, $template, $vars) {
        if ($this->db->exists('campaigns', 'name', $vars['campaign'])) {
            return parent::render($request, $response, $template, $vars);
        } else {
            return $this->notFound($request, $response);
        }
    }
}