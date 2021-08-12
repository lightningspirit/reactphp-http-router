# ReactPHP HTTP Router

Bootstrap a PHP HTTP Router in seconds.

### Choosen Tech
* [nikic/FastRoute](https://github.com/nikic/FastRoute)
* [ReactPHP/HTTP](https://github.com/reactphp/http)
* [Monolog](https://github.com/Seldaek/monolog)
* [League/Container](https://github.com/thephpleague/container)
* [League/Config](https://github.com/thephpleague/config)

### Why ReactPHP?

Like Node.js (and unlike traditional PHP applications), ReactPHP does not spawn a new
process for each request, which means that a single process can handle multiple requests
at the same time. IO is defered to other threads maintaining the main thread unblocked
which makes it more efficient and suitable for containerized systems.

### Organization

* `entrypoint.php` is the main script that loads Composer's autoload and 
bootstraps the HTTP server.
* `routes.php` is where one defines all possible routes.
* `config.php` holds all schema and config values, including the `IoC` one.
* `acme` has a couple of PHP classes and your logic can be added here too.

### First usage

1. Start the server
```
PORT=9001 php entrypoint.php
```
2. And access the example route
```
curl -v localhost:9001/hello-world?foo=bar
```
3. Enjoy!

