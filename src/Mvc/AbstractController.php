<?php

namespace Subapp\WebApp\Mvc;

use Subapp\WebApp\Controller;
use Subapp\WebApp\ServiceLocator\ServiceLocatorAwareTrait;

/**
 * Class AbstractController
 * @package Subapp\WebApp\Mvc
 */
abstract class AbstractController extends Controller
{
    
    use ServiceLocatorAwareTrait;
    
}