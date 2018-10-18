<?php

namespace Action;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

abstract class GenericAction {

    protected $view;
    protected $db;
    protected $notFoundHandler;

    public function __construct($container) {
        /* @var $container \TypeHinter */
        $this->db = $container->db;
        $this->view = $container->view;
        $this->notFoundHandler = $container->notFoundHandler;
    }

    abstract public function __invoke(Request $request, Response $response, $args);

    public function render(\Request $request, \Response $response, $template, $vars) {
        return $this->view->render($response, $template,
            array_merge($vars, [
                'copy'      => self::loadJSON('copy'),
                'uri'       => $request->getUri()->getPath() . '?' . $request->getUri()->getQuery(),
                'languages' => \Language::values(),
            ])
        );
    }

    protected function notFound(\Request $request, \Response $response) {
        return ($this->notFoundHandler)($request, $response);
    }

    private static function loadJSON($config) {
        return json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/config/$config.json"), true);
    }
}