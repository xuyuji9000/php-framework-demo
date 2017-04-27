<?php

require_once(__DIR__.'/../vendor/autoload.php');

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing;


$request = Request::createFromGlobals();
$routes = include(__DIR__ . '/../src/app.php');

$context = new Routing\RequestContext();
$context->fromRequest($request);
$matcher = new Routing\Matcher\UrlMatcher($routes, $context);

function render_template($request)
{
    ob_start();
    include(sprintf(__DIR__ . '/../src/pages/%s.php', $request->attributes->all()['_route']));
    return new Response(ob_get_clean());
}

try {
    $request->attributes->add($matcher->match($request->getPathInfo()));
    $response = call_user_func($request->attributes->get('_controller'), $request);
} catch (Routing\Exception\ResourceNotFoundException $e) {
    $response = new Response('Not Found', 404);
} catch (Exception $e) {
    $response = new Response('An error occurred', 500);
}

$response->send();
