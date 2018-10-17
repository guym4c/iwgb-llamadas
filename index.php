<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

require __DIR__ . '/vendor/autoload.php';
spl_autoload_register(function ($class) {
    include __DIR__ . '/src/' . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
});



// setup Slim

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$app = new Slim\App(['settings' => $config]);

session_start();

$container = $app->getContainer();

// Database

$container['db'] = function ($c) {
    return new Database();
};

// CSRF

$container['csrf'] = function ($c) {
    return new Slim\Csrf\Guard;
};

// Twig

$container['view'] = function ($container) {
    $templates = __DIR__ . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR;
//   $cache = __DIR__ . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR;
    $debug = false;
    $debug = true;
    $view = new Slim\Views\Twig($templates, compact('cache', 'debug'));
    $view->getEnvironment()->addGlobal('_get', $_GET);

    if ($debug) {
        $view->addExtension(new \Slim\Views\TwigExtension(
            $container['router'],
            $container['request']->getUri()
        ));
        $view->addExtension(new \Twig_Extension_Debug());
    }
    return $view;
};

// 404

$container['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return $c->view->render($response, '404.html.twig')->withStatus(404);
    };
};

// Filters

$container->get('view')->getEnvironment()->addFilter(
    new Twig_SimpleFilter('nicedate', function ($s) {
        return date('d-m-y Hi', strtotime($s));
    })
);

$container->get('view')->getEnvironment()->addFilter(
    new Twig_SimpleFilter('md', function ($s) {
        return (new Parsedown())->text($s);
    })
);

// Middleware

$languages = [];
foreach (Language::values() as $language) {
    $languages['languages'][] = $language->getValue();
}
$languages['callbacks'][] = function ($language) use ($container) {
    $container->view->getEnvironment()->addGlobal('_lang', $language);
};

$app->add(new McAskill\Slim\Polyglot\Polyglot($languages));
$app->add($container->get('csrf'));

// Routes

$app->get('/{campaign}', Action\LogIn::class);

$app->get('/{campaign}/{caller}', Action\MakeCall::class);

$app->post('/callback', Action\Callback::class);

$app->run();