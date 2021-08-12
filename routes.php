<?php

use FastRoute\RouteCollector;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;

/**
 * IMPORTANT: Do not use Closure functions
 * if you intend to play with routing cache
 * mechanism which is disabled by default.
 */
return function (RouteCollector $router) {

  /** 
   * Examples:
   * --------
   *  /users
   *  /user/{id:\d+} (matches /user/42, but not /user/xyz)
   *  /user/{name}[/{id:[0-9]+}] (optional id part)
   *  /user/{name:.+} (matches /user/foo/bar as well)
   */
  $router->get('/{text}', function (
    ServerRequestInterface $request, 
    string $text
  ) {
    return new Response(200, [
        'Content-Type' => 'application/json'
      ], json_encode([
        'path' => $text,
        'query' => $request->getQueryParams(),
      ])
    );
  });

};
