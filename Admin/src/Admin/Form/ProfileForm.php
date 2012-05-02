<?php
namespace Admin\Form;

use Zend\Form\Form,
    Zend\Form\Element,
    Zend\Validator\Validator;

class ProfileForm extends Form
{
    /**
     * @var Zend\Validator\Db\NoRecordExists
     */
    protected $usernameValidator;
    
    /**
     * @var Zend\Validator\Db\NoRecordExists
     */
    protected $emailValidator;
    
    /**
     * (non-PHPdoc)
     * @see Zend\Form.Form::init()
     */
    public function init()
    {
    }
    
    /**
     * Hack car init est appeler en même temps que le constructeur du coup 
     * les validateur inséré via le DI valent NULL. 
     * 
     */
    public function initLate()
    {
        $this->setName('my-account');
                  
        $this->addElement('text', 'username', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('StringLength', true, array(3, 255)),
                //$this->usernameValidator, TODO En attente correction ZF2
            ),
            'required'   => true,
            'label'      => 'Username',
            'order'      => 100,
        ));
        
        $this->addElement('text', 'firstname', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('StringLength', true, array(3, 128))
            ),
            'required'   => true,
            'label'      => 'Firstname',
            'order'      => 200,
        ));
        
        $this->addElement('text', 'lastname', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('StringLength', true, array(3, 128))
            ),
            'required'   => true,
            'label'      => 'Lastname',
            'order'      => 300,
        ));
        
        $this->addElement('text', 'email', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
                'EmailAddress',
                //$this->emailValidator, TODO En attente correction ZF2
            ),
            'required'   => true,
            'label'      => 'Email',
            'order'      => 400,
        ));

        $this->addElement('password', 'password', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('StringLength', true, array(6, 128))
            ),
            'required'   => true,
            'label'      => 'Password',
            'order'      => 500,
        ));

        $this->addElement('password', 'passwordVerify', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
               array('Identical', false, array('token' => 'password'))
            ),
            'required'   => true,
            'label'      => 'Password Verify',
            'order'      => 505,
        ));
        
        $this->addElement('hash', 'csrf', array(
            'ignore'   => true,
            'required' => true,
        ));

        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'order'    => 1000,
        ));
    }
    
    /**
     * Setter pour le DI.
     * 
     * @param Validator $emailValidator
     */
    public function setEmailValidator($emailValidator)
    {
        $this->emailValidator = $emailValidator;
        return $this;
    }
    
    /**
     * Setter pour le DI.
     * 
     * @param Validator $usernameValidator
     */
    public function setUsernameValidator($usernameValidator)
    {
        $this->usernameValidator = $usernameValidator;
        $this->initLate(); // Hack //TODO
        return $this;
    }
}
