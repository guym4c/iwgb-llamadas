<?php
/**
 * Created by PhpStorm.
 * User: hello
 * Date: 18/10/2018
 * Time: 00:08
 */

namespace Action;


class NewCaller extends CampaignAction {

    public function __invoke($request, $response, $args) {
        return $this->render($request,$response, 'newCaller.html.twig', ['campaign' => $args['campaign']]);
    }
}