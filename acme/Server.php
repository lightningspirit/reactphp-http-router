<?php

namespace Acme;

use React\Socket\ServerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use React\Http\HttpServer;

/**
 * @since 0.1.0
 */
final class Server
{

  protected ServerInterface $socket;
  protected Router $router;
  protected LoggerInterface $logger;
  protected HttpServer $http;

  public function __construct(ServerInterface $socket, Router $router, LoggerInterface $logger)
  {
    $this->socket = $socket;
    $this->router = $router;
    $this->logger = $logger;

    $this->http = new HttpServer(function (ServerRequestInterface $request) {
      $response = $this->handle($request);

      $this->logger->info(
        $response->getStatusCode() . ' '
          . $request->getMethod() . ' '
          . $request->getRequestTarget()
      );

      return $response;
    });
  }

  public function handle(ServerRequestInterface $request)
  {
    return $this->router->__invoke($request);
  }

  public function run(callable $callback = null)
  {
    $this->http->listen($this->socket);
    if ($callback) $callback();
  }
}
