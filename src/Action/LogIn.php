<?php
/**
 * Created by PhpStorm.
 * User: hello
 * Date: 17/10/2018
 * Time: 14:59
 */

namespace Action;


class LogIn extends GenericAction {

    public function __invoke($request, $response, $args) {
        return $this->render($request, $response, 'logIn.html.twig', []);
    }
}