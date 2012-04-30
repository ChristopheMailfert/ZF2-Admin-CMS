<?php

namespace Admin\View\Exception;

use Zend\Mvc\Exception,
    RuntimeException;

class MissingLocatorException extends RuntimeException implements Exception
{}
