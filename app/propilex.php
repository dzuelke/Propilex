<?php

$app = require __DIR__ . '/build.php';

// Config
$app['debug']                 = 'dev' === getenv('APPLICATION_ENV');
$app['translation.fallback']  = 'en';
$app['acceptable_mime_types'] = [ 'application/hal+xml', 'application/hal+json' ];
$app['curies_prefix']         = 'p';
$app['curies_route_name']     = 'curies_get';

/**
 * Entry point
 */
$app
    ->get('/', 'Propilex\Controller\HomeController::indexAction')
    ->bind('home');

/**
 * Documents
 */
$app
    ->get('/documents', 'Propilex\Controller\DocumentController::listAction')
    ->bind('document_list');

$app
    ->get('/documents/{id}', 'Propilex\Controller\DocumentController::getAction')
    ->assert('id', '\d+')
    ->bind('document_get');

$app
    ->post('/documents', 'Propilex\Controller\DocumentController::postAction')
    ->bind('document_post');

$app
    ->put('/documents/{id}', 'Propilex\Controller\DocumentController::putAction')
    ->assert('id', '\d+')
    ->bind('document_put');

$app
    ->delete('/documents/{id}', 'Propilex\Controller\DocumentController::deleteAction')
    ->assert('id', '\d+')
    ->bind('document_delete');

/**
 *  Curies
 */
$app->get('/rels/{rel}', function ($rel) use ($app) {
    if (is_file($file = sprintf(__DIR__ . '/../src/Propilex/Resources/%s.md', $rel))) {
        return $app['markdown']->transformMarkdown(file_get_contents($file));
    }

    $app->abort(404);
})->bind($app['curies_route_name']);

return $app;
