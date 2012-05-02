<?php
namespace Admin\Model;

use Zend\Db\TableGateway\TableGateway,
    Zend\Db\Adapter\Adapter,
    Zend\Db\ResultSet\ResultSet;

class RuleTable extends TableGateway
{
    
    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null)
    {
        return parent::__construct('admin_rule', $adapter, $databaseSchema, $selectResultPrototype);
    }
    
    /**
     * Récupère toutes les règle d'un rôle/
     * 
     * @param int $role_id
     */
    public function getRulesByRoleId($role_id)
    {
        return $this->select(array('role_id' => $role_id));
    }
}