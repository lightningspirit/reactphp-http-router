#!/usr/bin/env php
<?php

require __DIR__ . '/vendor/autoload.php';

use React\Socket\SocketServer;

$config = Acme\config();
$logger = Acme\logger();

$port = $config->get('server.port');
$host = $config->get('server.host');

$env = $config->get('env');
$logger->info("Starting in $env...");

(new \Acme\Server(
  new SocketServer("$host:$port"),
  new \Acme\Router(
    include 'routes.php',
    $config->get('routing.cache.enabled')
  ),
  $logger,
))->run(function () use ($logger, $host, $port) {
  $logger->info("Listening on $host:$port");
});
