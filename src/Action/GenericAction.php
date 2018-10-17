<?php

namespace Action;

abstract class GenericAction {

    protected $view;
    protected $db;
    protected $csrf;
    protected $notFoundHandler;

    public function __construct($container) {
        /* @var $container \TypeHinter */
        $this->db = $container->db;
        $this->view = $container->view;
        $this->csrf = $container->csrf;
        $this->notFoundHandler = $container->notFoundHandler;
    }

    abstract public function __invoke($request, $response, $args);

    public function render($request, $response, $template, $vars) {
        return $this->view->render($response, $template,
            array_merge($vars, [
                'csrfValues' => [
                    'name' => $this->csrf->getTokenNameKey(),
                    'value'=> $this->csrf->getTokenValueKey(),
                ],
                'copy' => self::loadJSON('copy'),
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