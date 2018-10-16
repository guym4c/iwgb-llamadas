<?php

class TypeHinter {

    /** @var $db Database */
    public $db;

    /** @var $csrf Slim\Csrf\Guard */
    public $csrf;

    /** @var $view Slim\Views\Twig */
    public $view;

    /** @var $notFound callable */
    public $notFoundHandler;
}