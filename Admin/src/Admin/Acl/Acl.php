<?php
namespace Admin\Acl;

use Zend\Acl\Acl as BaseAcl,
    Zend\Mvc\MvcEvent;

class Acl extends BaseAcl
{
    /**
     * @var Admin\Model\RoleTable
     */
    protected $roleTable;
    
    /**
     * @var Admin\Model\RuleTable
     */
    protected $ruleTable;
    
    /**
     * Initiate resources.
     * 
     * @param array $config
     */
    public function initResources($config)
    {
        // Récupère la config pour les resources.
        foreach ($config as $resource) {
            $this->addResource($resource->resource);
            if(isset($resource->children)) {
                $this->initChildrenResources($resource->children, $resource->resource);
            }
        }

        // Fait une requête pour récupérer les rôles.
        $roles = $this->roleTable->getAllRole();
        foreach ($roles as $role) {
            $this->addRole($role->name);

            $rules = $this->ruleTable->getRulesByRoleId($role->role_id);
            foreach ($rules as $rule) {
                if($rule->resource == 'all') {
                     if($rule->permission == 'allow') {
                         $this->allow($role->name);
                     }
                } else {
                    switch($rule->permission) {
                        case 'allow':
                            $this->allow($role->name, $rule->resource);
                            break;
                        case 'deny':
                        default:
                             $this->deny($role->name, $rule->resource);
                            break;
                    }
                }
            }
        }

        // add default roles
        $this->addResource('admin/login');
        $this->allow(null, 'admin/login');

        $this->addResource('admin/logout');
        $this->allow(null, 'admin/logout');
    }

    /**
     * Load children resources
     * 
     * @param \Zend\Config $config
     * @param string $parent
     */
    protected function initChildrenResources($config, $parent)
    {
        // Récupère la config pour les resources.
        foreach ($config as $resource) {
            $this->addResource($resource->resource, $parent);
            if(isset($resource->children)) {
                $this->initChildrenResources($resource->children, $resource->resource);
            }
        }
    }
	
    /**
     * @return RoleTable
     */
    public function getRoleTable ()
    {
        return $this->roleTable;
    }

	/**
     * @return RuleTable
     */
    public function getRuleTable ()
    {
        return $this->ruleTable;
    }

    /**
     * @param RoleTable
     */
    public function setRoleTable($roleTable)
    {
        $this->roleTable = $roleTable;
    }

    /**
     * @param RuleTable
     */
    public function setRuleTable($ruleTable)
    {
        $this->ruleTable = $ruleTable;
    }
}