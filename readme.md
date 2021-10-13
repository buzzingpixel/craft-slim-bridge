# Slim Bridge for Craft CMS

This is probably only of interest to me, but you're welcome to use this project if you come across it and like it.

I really like working in [Slim](https://www.slimframework.com), with [FastRoute](https://github.com/nikic/FastRoute), and PSR standard interfaces. To that end, I've created this extremely simple Craft Plugin that let's me do the front-end of Craft sites in Slim and mostly ignore that Craft is there in the background.

## Here's how to use it:

1. In your craft project, run `composer require buzzingpixel/craft-slim-bridge`
2. Then run `php craft plugin/install slim-bridge`
3. Create a config file in your `/config` directory named `slim-bridge.php`. See [Config](#config) section below for configuration.
4. Add the following catch-all route as the last route in your craft `routes.php` file:
    - `'<path:.*>' => 'slim-bridge/route-handler/index',`
5. For Category or Entry routing through Slim, use the following format in the site settings `template` field to route to custom class's `__invoke` method:
    - `_slimBridge/\My\Custom\Action\Class`
    - If you want to add route middleware or manipulate the route in other ways, implement `\BuzzingPixel\SlimBridge\ElementSetRoute\SetRouteFromParsed\RoutingCallbackContract`

Example:

```php
return [
    '/some/craft/route' => 'some-template-or-action',
    '/another/craft/route' => 'another-template-or-action',
    '<path:.*>' => 'slim-bridge/route-handler/index',
];
```

## Config

Two keys are available in the config file ([See example](examples/slim-bridge.php)):

### `containerInterface`

Slim requires an implementation of `\Psr\Container\ContainerInterface` and I've left this to project owners to provide. I, personally like [PHP-DI](https://php-di.org).

### `appCreatedCallback`

This is optional, but if you don't use it, then you won't have set any routes or middlewares and it will be useless to you. Once the app has been created, this callback will be run and the argument recieved will be the Slim App instance which you can use to set routes and middleware.
