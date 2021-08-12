<?php

namespace Acme;

use League\Config\Configuration;
use League\Config\ConfigurationInterface;
use League\Container\Container;
use League\Container\DefinitionContainerInterface;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

/**
 * Get the global Configuration instance
 * 
 * @since 0.1.0
 */
function config(): ConfigurationInterface
{
  static $cached = null;

  if ($cached == null) {
    [
      $schema,
      $mutator,
    ] = require_once __DIR__ . '/../config.php';
    
    $cached = $mutator(new Configuration($schema));
  }

  return $cached;
}

/**
 * Get the global defined Container instance
 * 
 * @since 0.1.0
 */
function container(): DefinitionContainerInterface
{
  static $cached = null;

  if ($cached == null) {
    $container = config()->get('container')
      ?: function (DefinitionContainerInterface $container) {
        return $container;
      };

    $cached = $container(new Container());
  }

  return $cached;
}

/**
 * Get the global defined Logger instance
 * 
 * @since 0.1.0
 */
function logger(): LoggerInterface
{
  static $cached = null;

  if ($cached == null) {
    $config = config();

    $streamHandler = new StreamHandler(
      $config->get('logging.path'),
      $config->get('logging.level')
    );
    $streamHandler->setFormatter(
      new LineFormatter($config->get('logging.format'))
    );

    $cached = new Logger(
      $config->get('logging.channel')
    );
    $cached->pushHandler($streamHandler);
  }

  return $cached;
}
