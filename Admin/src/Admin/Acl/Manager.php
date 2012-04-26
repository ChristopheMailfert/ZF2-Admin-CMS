<?php
namespace Admin\Acl;

class Manager
{
    protected $auth;
    
    
	/**
     * Cette fonction vérifie si l'utilisateur à le droit d'accéder a cette route.
     * 
     * @param MvcEvent $e
     */
    public function checkAcl($e)
    {
        //\Zend\Debug::dump($this->auth->getAuthService()->getIdentity());
        $target = $e->getTarget();
        $locator = $target->getLocator();
        
        $acl = $locator->get('Admin\Acl\Acl');
        
        $navigation = $locator->get('Zend\View\Helper\Navigation');
        $navigation->setRole('admin'); //TODO
        
        $role = 'admin'; //TODO

        $routeMatch = $e->getRouteMatch();
        $routeMatchName = $routeMatch->getMatchedRouteName();
        
        if (!$acl->isAllowed($role, $routeMatchName)) {
            $response = $e->getResponse();
            $response->setStatusCode(302);
                
            $renderer     = $locator->get('Zend\View\Renderer\PhpRenderer');
            $content = $renderer->render('admin/login.phtml');
            
            $e->getResponse()->setContent($content);
            return $e->getResponse();
        }
    }
    
    /**
     * @return the $auth
     */
    public function getAuth ()
    {
        return $this->auth;
    }

	/**
     * @param field_type $auth
     */
    public function setAuth ($auth)
    {
        $this->auth = $auth;
    }
    
}
?>