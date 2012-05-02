<?php
namespace Admin\Authentication;

use Zend\Authentication\AuthenticationService as BaseAuthenticationService;

class AuthenticationService extends BaseAuthenticationService
{
    public function setService($storage, $namespace)
    {
        $className = "\\Zend\\Authentication\\Storage\\" . ucfirst($storage);
        $this->setStorage(new $className($namespace));
    }
}