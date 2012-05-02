<?php

namespace Admin\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin,
    Admin\Authentication\AuthenticationService,
    Zend\Authentication\Adapter\DbTable,
    Zend\Authentication\Adapter\AdapterInterface;

class AdminAuthentication extends AbstractPlugin
{
    /**
     * @var Zend\Authentication\Adapter\AdapterInterface
     */
    protected $authAdapter;

    /**
     * @var AuthenticationSerivce
     */
    protected $authService;
    
    /**
     * Proxy convenience method 
     * 
     * @return bool
     */
    public function hasIdentity()
    {
        return $this->getAuthService()->hasIdentity();
    }
    
    /**
     * Proxy convenience method 
     * 
     * @return mixed
     */
    public function getIdentity()
    {
        return $this->getAuthService()->getIdentity();
    }

    /**
     * Get authAdapter.
     *
     * @return AdminAuthentication
     */
    public function getAuthAdapter()
    {
        if (null === $this->authAdapter) {
            $this->setAuthAdapter(new DbTable);
        }
        return $this->authAdapter;
    }
 
    /**
     * Set authAdapter.
     *
     * @param Zend\Authentication\Adapter\AdapterInterface $authAdapter
     */
    public function setAuthAdapter(AdapterInterface $authAdapter)
    {
        $this->authAdapter = $authAdapter;
        return $this;
    }
 
    /**
     * Get authService.
     *
     * @return AuthenticationService
     */
    public function getAuthService()
    {
        if (null === $this->authService) {
            $this->setAuthService(new AuthenticationService);
        }
        return $this->authService;
    }
 
    /**
     * Set authService.
     *
     * @param AuthenticationService $authService
     */
    public function setAuthService(AuthenticationService $authService)
    {
        $this->authService = $authService;
        return $this;
    }
}
