<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\ActionController,
    Zend\View\Model\ViewModel,
    Admin\Administrator,
    Admin\Form\LoginForm;

/**
 * 
 * 
 * @author "Christophe MAILFERT <cmailfert@takeatea.com>"
 * 
 */
class AdminController extends ActionController implements Administrator
{
    
    /**
     * Dashboard
     * 
     * @return ViewModel
     */
    public function indexAction()
    {
        return new ViewModel();
    }
    
    /**
     * Login Page without Layout.
     * 
     * @return ViewModel|Zend\Http\Response
     */
    public function loginAction()
    {
        if ($this->adminAuthentication()->getAuthService()->hasIdentity()) {
            return $this->redirect()->toRoute('admin');
        }
        
        $form = new LoginForm();
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            $formData = $request->post()->toArray();
            if ($form->isValid($formData)) {
                $adapter = $this->adminAuthentication()->getAuthAdapter();
                $adapter->setIdentity($formData['username']);
                $adapter->setCredential($formData['password']);
                
                $auth = $adapter->authenticate();
                if ($auth->isValid()) {
        			$data = $adapter->getResultRowObject(null, 'password');
        			$this->adminAuthentication()->getAuthService()->getStorage()->write($data);
        			
                  	return $this->redirect()->toRoute('admin'); 
        		} else {
        		    $form->addError('Username or Password not valid.');
        		}
            }
        }
        
        $result = new ViewModel(array('form' => $form));
        $result->setTerminal(true); // Don't display layout.
        
        return $result;
    }
    
    /**
     * Logout Action.
     * 
     * @return Zend\Http\Response
     */
    public function logoutAction()
    {
        $this->adminAuthentication()->getAuthService()->clearIdentity();
        return $this->redirect()->toRoute('admin/login');
    }
}
