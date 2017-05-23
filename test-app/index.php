<?php

namespace TestApp;

error_reporting(1);
ini_set('display_errors', 1);

// composer autoload
use Colibri\Parameters\ParametersCollection;
use Colibri\WebApp\Application\ConfigurableApplication;

include_once '../vendor/autoload.php';

set_exception_handler(function ($exception) {
  /** @var \Throwable $exception */
  $message = get_class($exception) . ': ' . $exception->getMessage();
  die(sprintf('<h3>%s</h3><hr><pre>%s</pre>', $message, $exception->getTraceAsString()));
});

class TestApp extends ConfigurableApplication
{
  protected function boot()
  {
    error_reporting(1);
    ini_set('display_errors', 1);
  }
}

(new TestApp(ParametersCollection::createFromFile(__DIR__ . '/config/app.php')))->run();