<?php
namespace Admin;

use Zend\Mvc\Router\RouteMatch,
    Zend\Mvc\MvcEvent,
    Zend\Module\Manager,
    Zend\EventManager\StaticEventManager,
    Zend\Module\Consumer\AutoloaderProvider;

class Module implements AutoloaderProvider
{
    
    public function init(Manager $moduleManager)
    {
        $events = StaticEventManager::getInstance();
        
        $events->attach('bootstrap', 'bootstrap', array($this, 'initializeView'), 100);
        $events->attach('bootstrap', 'bootstrap', array($this, 'initializeAcl'), 100);
        
        $events->attach('Zend\Module\Manager', 'loadModules.post', array($this, 'initializeNavigation'), 0);
        
        $events->attach('Zend\Mvc\Application', 'route', array($this, 'checkAdminLogin'), -100);
        $events->attach('Zend\Mvc\Application', 'route', array($this, 'addRouteMatch'), -200);
        
    }
    
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    /**
     * Vérifie que le user est loggé pour accéder à l'admin.
     * 
     * @param MvcEvent $e
     */
    public function checkAdminLogin(MvcEvent $e)
    {
        $controllerName = $e->getRouteMatch()->getParam('controller');
        $actionName = $e->getRouteMatch()->getParam('action');
        
        $target = $e->getTarget();
        $locator = $target->getLocator();
        $controller = $locator->get($controllerName);
        
        if($controller instanceof Administrator) {
            
            $auth = $locator->get('Admin\Authentication\AuthenticationService');
            if(!$auth->hasIdentity() && $actionName != 'login') {
            
                $response = $e->getResponse();
                $response->setStatusCode(302);
                
                $renderer     = $locator->get('Zend\View\Renderer\PhpRenderer');
                $content = $renderer->render('admin/login.phtml');
                
                $e->getResponse()->setContent($content);
                return $e->getResponse();
            }
        }
    }
    
    /**
     * Intialize la Vue.
     * 
     * @param MvcEvent $e
     */
    public function initializeView($e)
    {
        $app          = $e->getParam('application');
        $basePath     = $app->getRequest()->getBasePath();
        $locator      = $app->getLocator();
        
        $renderer     = $locator->get('Zend\View\Renderer\PhpRenderer');
        $renderer->plugin('basePath')->setBasePath($basePath);
        
        \Zend\Navigation\Page\Mvc::setDefaultUrlHelper($renderer->plugin('url'));
        
        $renderer->headTitle()->setSeparator(' - ')
                          ->setAutoEscape(false)
                          ->append('Take A Tea - Administration');

        $favicon = '<link rel="shortcut icon" href="' . $basePath . '/images/favicon.ico">';
        $renderer->plugin('placeHolder')->__invoke('favicon')->set($favicon);
    }
    
    /**
     * Initialize la navigartion
     * 
     * @param MvcEvent $e
     */
    public function initializeNavigation( $e)
    {
        $config = $e->getConfigListener()->getMergedConfig();
        $navigation = new \Zend\Navigation\Navigation($config->navigation->toArray());
        \Zend\Registry::set('Zend_Navigation', $navigation);
    }
    
    /**
     * Initialize les Acl
     * 
     * @param MvcEvent $e
     */
    public function initializeAcl($e)
    {
        $app = $e->getParam('application');
        $locator = $app->getLocator();
        
        $config = $e->getParam('config');
        
        $acl = $locator->get('Admin\Acl\Acl');
        $acl->initResources($config->acl);
        
        $events = StaticEventManager::getInstance();
        $AclManager = $locator->get('Admin\Acl\Manager');
        $events->attach('Zend\Mvc\Controller\ActionController', 'dispatch', array($AclManager, 'checkAcl'), 100);
    }
    
    /**
     * //TODO supprimer cette methode car elle st moche.
     * 
     */
    public function addRouteMatch($e)
    {
        $routeMatch = $e->getRouteMatch();
        $navigation = \Zend\Registry::get('Zend_Navigation');
        foreach ($navigation->getPages() as $page) {
            if($page instanceof \Zend\Navigation\Page\Mvc) {
                $page->setRouteMatch($routeMatch);
            }
            if($page->hasPages()) {
                $this->addRouteMatchChildren($page->getPages(), $routeMatch);
            }
        }
    }
    
    private function addRouteMatchChildren($pages, $routeMatch)
    {
        foreach ($pages as $page) {
            if($page instanceof \Zend\Navigation\Page\Mvc) {
                $page->setRouteMatch($routeMatch);
            }
            if($page->hasPages()) {
                $this->addRouteMatchChildren($page->hasPages(), $routeMatch);
            }
        }
    }
}
