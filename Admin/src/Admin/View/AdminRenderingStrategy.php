<?php

namespace Admin\View;

use Admin\Controller\AdminInterface,
    Zend\Di\Locator,
    Zend\EventManager\EventCollection,
    Zend\EventManager\ListenerAggregate,
    Zend\Mvc\MvcEvent,
    Zend\View\View,
    Zend\View\Model as ViewModel;

class AdminRenderingStrategy implements ListenerAggregate
{
    /**
     * @var \Zend\Stdlib\CallbackHandler[]
     */
    protected $listeners = array();

    /**
     * Layout template - template used in root ViewModel of MVC event.
     *
     * @var string
     */
    protected $layoutTemplate = 'layout/admin/default';
    
    /**
     * Layout login template - template used in root ViewModel of MVC event.
     *
     * @var string
     */
    protected $layoutLoginTemplate = 'layout/admin/login';

    /**
     * @var View
     */
    protected $view;

    /**
     * Set view
     *
     * @param  View $view
     * @return DefaultRenderingStrategy
     */
    public function __construct(View $view)
    {
        $this->view = $view;
        return $this;
    }

    /**
     * Attach the aggregate to the specified event manager
     *
     * @param  EventCollection $events
     * @return void
     */
    public function attach(EventCollection $events)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_RENDER, array($this, 'render'), -100);
    }

    /**
     * Detach aggregate listeners from the specified event manager
     *
     * @param  EventCollection $events
     * @return void
     */
    public function detach(EventCollection $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }

    /**
     * Set layout template value
     *
     * @param  string $layoutTemplate
     * @return DefaultRenderingStrategy
     */
    public function setLayoutTemplate($layoutTemplate)
    {
        $this->layoutTemplate = (string) $layoutTemplate;
        return $this;
    }

    /**
     * Get layout template value
     *
     * @return string
     */
    public function getLayoutTemplate()
    {
        return $this->layoutTemplate;
    }
    
    /**
     * Set layout login template value
     *
     * @param  string $layoutTemplate
     * @return DefaultRenderingStrategy
     */
    public function setLayoutLoginTemplate($layoutTemplate)
    {
        $this->layoutLoginTemplate = (string) $layoutTemplate;
        return $this;
    }

    /**
     * Get layout login template value
     *
     * @return string
     */
    public function getLayoutLoginTemplate()
    {
        return $this->layoutLoginTemplate;
    }
    
    /**
     * Get the locator object
     *
     * @return null|Locator
     */
    public function getLocator()
    {
        return $this->locator;
    }
    
    /**
     * Set a service locator/DI object
     *
     * @param  Locator $locator
     * @return Application
     */
    public function setLocator(Locator $locator)
    {
        $this->locator = $locator;
        return $this;
    }

    /**
     * Render the view
     *
     * @param  MvcEvent $e
     * @return Response
     */
    public function render(MvcEvent $e)
    {
        $locator = $this->getLocator();
        if (!$locator) {
            throw new Exception\MissingLocatorException('Cannot render without a locator');
        }
        
        $controllerName = $e->getRouteMatch()->getParam('controller');
        $actionName = $e->getRouteMatch()->getParam('action');
        
        $controller = $locator->get($controllerName);
        if($controller instanceof AdminInterface) {
            $viewModel = $e->getViewModel();
            if($viewModel->hasChildren()) {
                if($actionName == 'login') {
                    $viewModel->setTemplate($this->getLayoutLoginTemplate());
                } else {
                    $viewModel->setTemplate($this->getLayoutTemplate());
                }
            }
        }
    }
}