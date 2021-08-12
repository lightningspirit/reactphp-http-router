<?php

namespace Acme;

use FastRoute\Dispatcher;
use LogicException;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;
use function FastRoute\cachedDispatcher;

/**
 * @since 0.1.0
 */
final class Router
{
  private $dispatcher;

  public function __construct(
    callable $route,
    bool $cacheEnabled = false,
    string $cacheFile = '',
  )
  {
    if ($cacheEnabled && '' == $cacheFile) {
      $cacheFile = sys_get_temp_dir() . '/route.cache';
    }

    $this->dispatcher = cachedDispatcher($route, [
      'cacheFile' => $cacheFile,
      'cacheDisabled' => !$cacheEnabled,
    ]);
  }

  public function __invoke(ServerRequestInterface $request)
  {
    $routeInfo = $this->dispatcher->dispatch($request->getMethod(), $request->getUri()->getPath());
    
    switch ($routeInfo[0]) {
      case Dispatcher::NOT_FOUND:
        return new Response(404, [
          'Content-Type' => 'application/json'
        ], json_encode([
          'error' => [
            'code' => 'NOT_FOUND',
            'message' => 'Not found',
          ]
        ]));
      case Dispatcher::METHOD_NOT_ALLOWED:
        return new Response(405, [
          'Allow' => $routeInfo[1].join(', '),
          'Content-Type' => 'application/json'
        ], json_encode([
          'error' => [
            'code' => 'METHOD_NOT_ALLOWED',
            'message' => 'Method Not Allowed',
          ]
        ]));
      case Dispatcher::FOUND:
        $params = $routeInfo[2];
        return $routeInfo[1]($request, ...array_values($params));
    }

    throw new LogicException('Something wrong with routing');
  }
}
