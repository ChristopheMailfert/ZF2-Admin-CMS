<?php
namespace Admin\Controller;

use Zend\View\Model\ViewModel,
    Admin\Administrator,
    Admin\Form\LoginForm;

/**
 * 
 * 
 * @author "Christophe MAILFERT <cmailfert@takeatea.com>"
 * @version 
 * 
 */
class RoleController extends AbstractController
{
    
    /**
     * Affichage la liste des roles
     * 
     * @return ViewModel
     */
    public function indexAction()
    {
        return new ViewModel();
    }
    
    /**
     * Redirige vers edit.
     * 
     */
    public function newAction()
    {
        
    }
    
    /**
     * Edition / Création d'un role.
     * 
     */
    public function editAction()
    {
        
    }
}
