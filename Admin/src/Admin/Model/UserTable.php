<?php
namespace Admin\Model;

use Zend\Db\TableGateway\TableGateway,
    Zend\Db\Adapter\Adapter,
    Zend\Db\ResultSet\ResultSet;

class UserTable extends TableGateway
{
    
    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null)
    {
        return parent::__construct('admin_user', $adapter, $databaseSchema, $selectResultPrototype);
    }
    
    /**
     * Récupère un Admin User en fonction de son user_id
     * 
     * @param int $user_id
     */
    public function getAdminUser($user_id)
    {
        $user_id  = (int) $user_id;
        $rowset = $this->select(array('user_id' => $user_id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $user_id");
        }
        return $row;
    }
    
    public function getAllAdminUser()
    {
        $resultSet = $this->select();
        return $resultSet;
    }
}