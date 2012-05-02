<?php
namespace Admin\Controller;

use Zend\View\Model\ViewModel,
    Admin\Administrator,
    Admin\Model\UserTable;

/**
 * 
 * 
 * @author "Christophe MAILFERT <cmailfert@takeatea.com>"
 * @version 
 * 
 */
class UserController extends AbstractController
{
    /**
     * @var Admin\Form\ProfileForm
     */
    protected $profileForm;
    
    /**
     * @var Admin\Model\UserTable
     */
    protected $userTable;
    
    /**
     * Affichage la liste des admin
     * 
     * @return ViewModel
     */
    public function indexAction()
    {
        $entries = $this->userTable->getAllAdminUser();
        
        $page    = $this->request->query()->get('page', 1);
        
        return new ViewModel(array(
            'entries' => $this->getPaginator($entries->toArray(), $page),
        ));
    }
    
    /**
     * Redirige vers edit.
     * 
     */
    public function newAction()
    {
        //TODO forward vers edit.
    }
    
    /**
     * Edition / Création d'un administrateur.
     * 
     */
    public function editAction()
    {
        
    }
    
    /**
     * Profile Page for the user connected.
     * 
     * @return ViewModel
     */
    public function profileAction()
    {
        // Récupération du formulaire instancié dans le DI.
        $form = $this->profileForm;
        
        // Récupération de l'identité de la personne connecté grace au plugin du controller.
        $identity = $this->adminAuthentication()->getAuthService()->getIdentity();
        
        // Requête pour récupérer les info.
        $user = $this->userTable->getAdminUser($identity->user_id);
        
        // Conversion au format array.
        $data = $user->getArrayCopy();
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            $formData = $request->post()->toArray();
            if ($form->isValid($formData)) {
                $formData['password'] = md5($formData['password']); //TODO Trouver mieux.
                unset($formData['csrf']); //TODO Trouver mieux.
                unset($formData['passwordVerify']); //TODO Trouver mieux.
                unset($formData['submit']); //TODO Trouver mieux.
                $this->userTable->update($formData, array('user_id' => $identity->user_id));
            } else {
                $form->populate($formData);
            }
        } else {
            $form->populate($data);
        }
        
        
        return new ViewModel(array('form' => $form));
    }
    
    /**
     * Création d'un paginator.
     * 
     * @param Iterator $it
     * @param int $page
     */
    public function getPaginator($it, $page)
    {
        $paginator = \Zend\Paginator\Paginator::factory($it);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage(10);
        $paginator->setPageRange(10);
        return $paginator;
    }
    
    /**
     * Setter pour le DI
     * 
     * @param UserTable $userTable
     */
    public function setUserTable(UserTable $userTable)
    {
        $this->userTable = $userTable;
        return $this;
    }
    
    /**
     * Getter pour le DI
     * 
     */
    public function getUserTable()
    {
        return $this->userTable;
    }
    
	/**
     * @return the $profileForm
     */
    public function getProfileForm ()
    {
        return $this->profileForm;
    }

	/**
     * @param ProfileForm $profileForm
     */
    public function setProfileForm ($profileForm)
    {
        $this->profileForm = $profileForm;
    }

    
}
