<?php
return array(
    'di' => array(
        'instance' => array(
            // Définition de tous les alias.
		'alias' => array(
            	'admin_email_validator'  => 'Zend\Validator\Db\NoRecordExists',
				'admin_username_validator'  => 'Zend\Validator\Db\NoRecordExists',
            ),
            // Connecteur à la base de données
            'Zend\Db\Adapter\Adapter' => array('parameters' => array(
                'driver' => array(
                    'driver'         => 'Pdo_MySQL',
                    'dsn'            => 'mysql:dbname=blog;hostname=localhost',
                    'username'       => 'root',
                    'password'       => 'root',
                    'driver_options' => array(
                        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
                    ),
                ),
            )),
            // Defini le path des vues.
            'Zend\View\Resolver\TemplatePathStack' => array('parameters' => array(
                'paths'  => array(
                    'admin' => __DIR__ . '/../view',
                ),
            )),
            'Zend\View\Resolver\TemplateMapResolver' => array(
                'parameters' => array(
                    'map'  => array(
                        'layout/admin/default' => __DIR__ . '/../view/layout/default.phtml',
                        'layout/admin/login' => __DIR__ . '/../view/layout/login.phtml',
                        'admin/login' => __DIR__ . '/../view/admin/login.phtml',
                        'admin/index' => __DIR__ . '/../view/admin/index.phtml',
                        'admin/breadcrumbs' => __DIR__ . '/../view/admin/breadcrumbs.phtml',
                        'admin/menu' => __DIR__ . '/../view/admin/menu.phtml',
                    ),
                ),
            ),
            // Configuration for the doctype helper.
            'Zend\View\Helper\Doctype' => array(
                'parameters' => array(
                    'doctype' => 'HTML5',
                ),
            ),
            // Ajoute un plugin au controller.
            'Zend\Mvc\Controller\PluginLoader' => array(
                'parameters' => array(
                    'map' => array(
                        'AdminAuthentication' => 'Admin\Controller\Plugin\AdminAuthentication',
                    ),
                ),
            ),
            // Définition DI pour AdminAuthentication.
            'Admin\Controller\Plugin\AdminAuthentication' => array(
                'parameters' => array(
                    'authAdapter' => 'Zend\Authentication\Adapter\DbTable',
                    'authService' => 'Admin\Authentication\AuthenticationService',
                ),
            ),
            // Définition DI pour l'adaptateur DbTable.
            'Zend\Authentication\Adapter\DbTable' => array(
                'parameters' => array(
                    'tableName' => 'admin_user',
                    'identityColumn' => 'username',
                	'credentialColumn' => 'password',
            		'credentialTreatment' => 'MD5(?) AND is_active = 1',
                ),
            ),
            // Définition DI pour AuthenticationService.
            'Admin\Authentication\AuthenticationService' => array(
                'params' => array(
                    'service'  => array('session', 'admin')
                ),
            ),
            // Ajout d'Helpers dans Navigation.
            'Zend\View\Helper\Navigation\HelperLoader' => array(
                'parameters' => array(
                    'map' => array(
                        'adminmenu' => 'Admin\View\Helper\Navigation\AdminMenu',
                    ),
                ),
            ),
            // Ajout d'Helpers
            'Zend\View\HelperLoader' => array(
                'parameters' => array(
                    'map' => array(
                        'auth' => 'Admin\View\Helper\Auth',
                    ),
                ),
            ),
            'Admin\View\Helper\Auth' => array(
                'parameters' => array(
                    'authService' => 'Admin\Authentication\AuthenticationService',
                ),
            ),
            // Définition DI pour Helper Navigation nécessaire pour ajouter un helper dans Navigation.
            'Zend\View\Helper\Navigation' => array(
                'parameters' => array(
                    'loader' => 'Zend\View\Helper\Navigation\HelperLoader',
                    'acl' => 'Admin\Acl\Acl'
                ),
            ),
            // Définition DI pour le controller System.
            'Admin\Controller\UserController' => array(
                'parameters' => array(
            		'profileForm'    => 'Admin\Form\ProfileForm',
            		'userTable'    => 'Admin\Model\UserTable',
                ),
            ),
            // Définition DI pour le formulaire Account.
            'Admin\Form\ProfileForm' => array(
                'parameters' => array(
                    'emailValidator'    => 'admin_email_validator',
                    'usernameValidator' => 'admin_username_validator'
                ),
            ),
            // Définition DI pour le validator admin_email_validator @see alias
            'admin_email_validator' => array(
                'parameters' => array(
                    'options' => array(
                        'table' => 'admin_user',
                        'field' => 'email',
                    ),
                ),
            ),
            // Définition DI pour le validator admin_username_validator @see alias
            'admin_username_validator' => array(
                'parameters' => array(
                    'options' => array(
                        'table' => 'admin_user',
                        'field' => 'username',
                    ),
                ),
            ),
            // Définition DI pour la Table Admin User
            'Admin\Model\UserTable' => array(
                'parameters' => array(
                    'adapter' => 'Zend\Db\Adapter\Adapter',
                )
            ),
            // Définition DI pour la Table Admin Role
            'Admin\Model\RoleTable' => array(
                'parameters' => array(
                    'adapter' => 'Zend\Db\Adapter\Adapter',
                )
            ),
            // Définition DI pour la Table Admin Rule
            'Admin\Model\RuleTable' => array(
                'parameters' => array(
                    'adapter' => 'Zend\Db\Adapter\Adapter',
                )
            ),
            // Définition DI pour les Acl
            'Admin\Acl\Acl' => array(
                'parameters' => array(
            		'roleTable'    => 'Admin\Model\RoleTable',
            		'ruleTable'    => 'Admin\Model\RuleTable',
                ),
            ),
            // Définition DI pour les Acl Manager
            'Admin\Acl\Manager' => array(
                'parameters' => array(
            		'auth'    => 'Admin\Controller\Plugin\AdminAuthentication',
                ),
            ),
            // Défini les routes.
            'Zend\Mvc\Router\RouteStack' => array(
                'parameters' => array(
       			'routes' => array(
         			'admin' => array(
           				'type' => 'Zend\Mvc\Router\Http\Literal',
           				'options' => array(
             			   	'route' => '/admin',
                       		'defaults' => array(
            			    	'controller' => 'Admin\Controller\AdminController',
                                'action' => 'index'
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'login' => array(
                                'type' => 'Zend\Mvc\Router\Http\Literal',
                                'options' => array(
                                    'route' => '/login',
                                    'defaults' => array(
                                        'controller' => 'Admin\Controller\AdminController',
                                        'action' => 'login'
                                    ),
                                ),
                            ),
                            'logout' => array(
                                'type' => 'Zend\Mvc\Router\Http\Literal',
                                'options' => array(
                                    'route' => '/logout',
                                    'defaults' => array(
                                        'controller' => 'Admin\Controller\AdminController',
                                        'action' => 'logout'
                                    ),
                                ),
                            ),
                            'user' => array(
                            	'type' => 'Zend\Mvc\Router\Http\Literal',
                                'options' => array(
                               		'route' => '/user',
                                    'defaults' => array(
                            			'controller' => 'Admin\Controller\UserController',
                                        'action' => 'index'
                                    ),
                                ),
                                'may_terminate' => true,
                        		'child_routes' => array(
                                    'profile' => array(
                                		'type' => 'Zend\Mvc\Router\Http\Literal',
                                        'options' => array(
                               				'route' => '/profile',
                                       		'defaults' => array(
                            			    	'controller' => 'Admin\Controller\UserController',
                                                'action' => 'profile'
                                            ),
                                        ),
                                    ),
                                    'edit' => array(
                                		'type' => 'Zend\Mvc\Router\Http\Regex',
                                        'options' => array(
                                    		'regex' => '/edit/(?<id>[a-zA-Z0-9_-]+)?',
                                       		'defaults' => array(
                            			    	'controller' => 'Admin\Controller\UserController',
                                                'action' => 'edit'
                                            ),
                                            'spec' => '/edit/%id%',
                                        ),
                                    ),
                                ),
                            ),
                            'role' => array(
                                'type' => 'Zend\Mvc\Router\Http\Literal',
                                'options' => array(
                             		'route' => '/role',
                                    'defaults' => array(
                            			'controller' => 'Admin\Controller\RoleController',
                                        'action' => 'index'
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            )),
        ),
    ),
    'navigation' => array(
    	'dashboard' => array(
            'label' => 'Dashboard',
    		'route' => 'admin',
            'resource' => 'admin',
            'active' => 1,
            'order' => -100
        ),
        'system' => array(
            'label' => 'System',
    		'uri' => '#',
            'resource' => 'admin',
            'icon' => 'icon-cog',
            'order' => 900,
            'pages' => array(
                'profile' => array(
                    'label' => 'My Profile',
                    'route' => 'admin/user/profile',
        			'resource' => 'admin/user/profile',
                    'order' => 10
                ),
                'user' => array(
                    'label' => 'User',
                    'route' => 'admin/user',
                	'resource' => 'admin/user',
                    'separator' => true,
                    'order' => 20
                ),
                'role' => array(
                    'label' => 'Role',
                    'route' => 'admin/role',
                	'resource' => 'admin/role',
                	'order' => 30
                ),
            ),
        ),
    ),
    'acl' => array(
        'admin' => array(
            'title' => 'Admin',
            'resource' => 'admin',
            'children' => array(
                'system' => array(
                    'title' => 'System',
            		'resource' => '',
                    'children' => array(
                        'profile' => array(
                            'title' => 'My account',
                    		'resource' => 'admin/user/profile',
                        ),
                        'user' => array(
                            'title' => 'Admin User',
                    		'resource' => 'admin/user',
                        	'children' => array(
                                'edit' => array(
                                	'title' => 'Edit User',
                                    'resource' => 'admin/user/edit',
                                ),
                            ),
                        ),
                        'role' => array(
                            'title' => 'Admin Role',
                    		'resource' => 'admin/role',
                        ),
                    ),
                ),
            ),
        ),
    ),
);
