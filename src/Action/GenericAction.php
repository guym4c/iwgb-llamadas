<?php

namespace Action;

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

    abstract public function __invoke($request, $response, $args);

    public function render($request, $response, $template, $vars) {
        return $this->view->render($response, $template,
            array_merge($vars, [
                'copy'      => self::loadJSON('copy'),
                'uri'       => $request->getUri()->getPath(),
                'languages' => \Language::values(),
            ])
        );
    }

    protected function notFound($request, $response) {
        return ($this->notFoundHandler)($request, $response);
    }

    private static function loadJSON($config) {
        return json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/config/$config.json"), true);
    }
}