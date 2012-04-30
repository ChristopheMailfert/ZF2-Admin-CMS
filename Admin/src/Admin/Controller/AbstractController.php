<?php

namespace Admin\Controller;

use Zend\Mvc\MvcEvent,
    Zend\Mvc\Controller\ActionController as BaseController,
    Zend\Stdlib\RequestDescription as Request,
    Zend\Stdlib\ResponseDescription as Response;

/**
 * 
 * 
 * @author "Christophe MAILFERT <cmailfert@takeatea.com>"
 * 
 */
class AbstractController extends BaseController implements AdminInterface
{
    /**
     * Register the default events for this controller
     *
     * @return void
     */
    protected function attachDefaultListeners()
    {
        $events = $this->events();
        $events->attach(MvcEvent::EVENT_DISPATCH, array($this,'checkAuth'), 200);
        $events->attach(MvcEvent::EVENT_DISPATCH, array($this, 'checkAcl'), 100);
        
        parent::attachDefaultListeners();
    }
    
    /**
     * Control authentication
     * @param MvcEvent $e
     * @return type 
     */
    public function checkAuth(MvcEvent $e)
    {
        $actionName = $e->getRouteMatch()->getParam('action');
        if($actionName == 'login') {
            return;
        }
        
        $auth = $this->getLocator()->get('Admin\Authentication\AuthenticationService');
        if(!$auth->hasIdentity()) {
            return $this->plugin('redirect')->toRoute('admin/login');
        }
    }
    
    /**
     * Control ACL authorisation
     * @param MvcEvent $e
     * @return type 
     */
    public function checkAcl(MvcEvent $e)
    {
        $actionName = $e->getRouteMatch()->getParam('action');
        if($actionName == 'login') {
            return;
        }
        
        $target = $e->getTarget();
        $locator = $target->getLocator();
        
        $acl = $locator->get('Admin\Acl\Acl');
        
        $navigation = $locator->get('Zend\View\Helper\Navigation');
        $navigation->setRole('admin'); //TODO
        
        $role = 'admin'; //TODO
        
        $routeMatchName = $e->getRouteMatch()->getMatchedRouteName();
        
        if (!$acl->isAllowed($role, $routeMatchName)) {
            return $this->plugin('redirect')->toRoute('login');
        }
    }
}
