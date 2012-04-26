<?php
namespace Admin\Model;

use Zend\Db\TableGateway\TableGateway,
    Zend\Db\Adapter\Adapter,
    Zend\Db\ResultSet\ResultSet;

class RoleTable extends TableGateway
{
    
    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null)
    {
        return parent::__construct('admin_role', $adapter, $databaseSchema, $selectResultPrototype);
    }
    
    /**
     * Retourne la liste de tous les roles.
     * 
     */
    public function getAllRole()
    {
        $resultSet = $this->select();
        return $resultSet;
    }
}