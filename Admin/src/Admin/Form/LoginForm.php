<?php
namespace Admin\Form;

use Zend\Form\Form;

class LoginForm extends Form
{
    public function init()
    {
        $this->setName('login');
        
        $this->addElement('text', 'username', array(
            'label'     => 'Username:',
            'required'  => true,
        	'filters'    => array(
                'StripTags',
            ),
        ));

        $this->addElement('password', 'password', array(
            'label'      => 'Password:',
            'required'   => true,
            'filters'    => array(
                'StripTags',
            ),
        ));

        $this->addElement('hash', 'csrf', array(
            'ignore'   => true,
            'required' => true,
        ));

        $this->addElement('submit', 'Login', array(
            'label'    => 'Login',
            'required' => false,
            'ignore'   => true,
        ));
    }
}
