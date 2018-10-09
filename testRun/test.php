<?php

use Subapp\WebApp\Application;
use Subapp\WebApp\ApplicationContainer;

include_once '../vendor/autoload.php';

new ApplicationContainer();

die(var_dump(
    Application::sampleConfiguration(),
    Application::sampleConfiguration()->toJSON(),
    Application::sampleConfiguration()->toPHP()
));