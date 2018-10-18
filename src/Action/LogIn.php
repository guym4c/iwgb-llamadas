<?php
/**
 * Created by PhpStorm.
 * User: hello
 * Date: 17/10/2018
 * Time: 14:59
 */

namespace Action;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class LogIn extends GenericAction {

    public function __invoke(Request $request, Response $response, $args) {
        return $this->render($request, $response, 'logIn.html.twig', []);
    }
}