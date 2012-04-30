<?php
namespace Admin;

use Zend\Mvc\Router\RouteMatch,
    Zend\EventManager\Event,
    Zend\Mvc\MvcEvent,
    Zend\Module\ModuleEvent,
    Zend\Module\Manager,
    Zend\EventManager\StaticEventManager,
    Zend\Module\Consumer\AutoloaderProvider,
    Zend\Navigation\Navigation;

class Module implements AutoloaderProvider
{
    public function init(Manager $moduleManager)
    {
        $events = StaticEventManager::getInstance();
        $events->attach('bootstrap', MvcEvent::EVENT_BOOTSTRAP, array($this, 'initializeAcl'), 100);
        $events->attach('bootstrap', MvcEvent::EVENT_BOOTSTRAP, array($this, 'initializeView'), 100);
        $events->attach('Zend\Module\Manager', 'loadModules.post', array($this, 'initializeNavigation'), -100);
        $events->attach('Zend\Mvc\Application', MvcEvent::EVENT_ROUTE, array($this, 'addRouteMatch'), -200);
        
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
    }
    
    /**
     * Initiate view components
     * 
     * @param MvcEvent $e
     */
    public function initializeView(Event $e)
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
        
        // add strategy
        $adminViewStrategy = $locator->get('Admin\View\AdminRenderingStrategy');
        $adminViewStrategy->setLocator($locator);
        $app->events()->attachAggregate($adminViewStrategy);
    }
    
    /**
     * Initiate navigation
     * 
     * @param MvcEvent $e
     */
    public function initializeNavigation(ModuleEvent $e)
    {
        $config = $e->getConfigListener()->getMergedConfig();
        $navigation = new Navigation($config->navigation->toArray());
        \Zend\Registry::set('Zend_Navigation', $navigation);
    }
    
    /**
     * //TODO delete this, otpmize
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
