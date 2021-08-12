<?php

use League\Config\ConfigurationBuilderInterface;
use League\Container\DefinitionContainerInterface;
use Monolog\Logger;
use Nette\Schema\Expect;

return [
  /** Config schema definition */
  [
    'env' => Expect::anyOf('production', 'development', 'testing'),
    'server' => Expect::structure([
      'host' => Expect::string()->default('localhost'),
      'port' => Expect::int()->min(1)->max(65535),
    ]),
    'routing' => Expect::structure([
      'cache' => Expect::structure([
        'enabled' => Expect::bool()->default(false),
      ]),
    ]),
    'logging' => Expect::structure([
      'level' => Expect::int()->default(Logger::ERROR),
      'path' => Expect::type('string|resource')->assert(function ($path) {
        return is_string($path) ? \is_writeable($path) : \is_resource($path);
      })->required(),
      'format' => Expect::string()->default("[%datetime%] %channel%.%level_name% %message%\n"),
      'channel' => Expect::string()->default("default"),
    ]),
    'container' => Expect::type('callable'),
  ],

  /** Config values */
  function (ConfigurationBuilderInterface $config) {
    $env = getenv('ENV') ?: 'development';

    $config->merge([
      'env'       => $env,
      'server'    => [
        'port'      => (int) getenv('PORT') ?: 80,
        'host'      => getenv('HOST') ?: '0.0.0.0',
      ],
      'routing'   => [
        'cache'     => [
          'enabled'   => getenv('ENABLE_ROUTING_CACHE'),
        ],
      ],
      'logging'   => [
        'level'     => getenv('DEBUG') ? Logger::DEBUG : Logger::INFO,
        'path'      => STDOUT,
        'format'    => "[%datetime%] %level_name% %message%\n",
      ],
      'container' => function (
        DefinitionContainerInterface $container
      ) {
        /** Refer to https://container.thephpleague.com/4.x */
        
        return $container;
      }
    ]);

    return $config;
  }
];
