<?php
namespace Admin\View\Helper;

use Zend\View\Helper\AbstractHelper;
/**
 * 
 * 
 * @author "Christophe MAILFERT <chris@takeatea.com>"
 *
 */
class Auth extends AbstractHelper
{
    /**
     * @var 
     */
    protected $authService;
    

    /**
     * __invoke 
     * 
     * @param string $name 
     * @return string
     */
    public function __invoke()
    {
        return $this->authService->getIdentity();
    }
    
	/**
     * @return the $authService
     */
    public function getAuthService ()
    {
        return $this->authService;
    }

	/**
     * @param field_type $authService
     */
    public function setAuthService ($authService)
    {
        $this->authService = $authService;
    }
}