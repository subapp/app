<?php

namespace Colibri\WebApp\Mvc;

use Colibri\WebApp\Controller;
use Colibri\WebApp\ServiceLocator\ServiceLocatorAwareTrait;

/**
 * Class AbstractController
 * @package Colibri\WebApp\Mvc
 */
abstract class AbstractController extends Controller
{
    
    use ServiceLocatorAwareTrait;
    
}