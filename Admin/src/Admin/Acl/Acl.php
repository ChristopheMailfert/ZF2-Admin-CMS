<?php
namespace Admin\Acl;

use Zend\Acl\Acl as AclBase;

class Acl extends AclBase
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
     * Initalize les resources.
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
	    
	    $this->addResource('admin/login');
	    $this->allow(null, 'admin/login');
	    
	    $this->addResource('admin/logout');
	    $this->allow(null, 'admin/logout');
	}
	
	/**
	 * Charges les resources enfant.
	 * 
	 * @param \Zend\Config $config
	 * @param string $parent
	 */
	public function initChildrenResources($config, $parent)
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
     * @return the $roleTable
     */
    public function getRoleTable ()
    {
        return $this->roleTable;
    }

	/**
     * @return the $ruleTable
     */
    public function getRuleTable ()
    {
        return $this->ruleTable;
    }

	/**
     * @param RoleTable $roleTable
     */
    public function setRoleTable ($roleTable)
    {
        $this->roleTable = $roleTable;
    }

	/**
     * @param RuleTable $ruleTable
     */
    public function setRuleTable ($ruleTable)
    {
        $this->ruleTable = $ruleTable;
    }
}