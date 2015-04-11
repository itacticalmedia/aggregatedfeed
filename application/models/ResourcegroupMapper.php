<?php

class Application_Model_ResourcegroupMapper extends Application_Model_MapperBase
{

    /**
     * Set table Name
     * @var string 
     */
    protected $_name = 'ResourceGroup';

    /**
     * 
     * @param Application_Model_Role $roles
     * @return boolean|int
     */
    public function save(Application_Model_Resourcegroup $resGrp)
    {
        $data = array(
            'name' => $resGrp->getName()
        );

        if (null === ($id = $resGrp->getId()))
        {
            $data['createdOn']= new Zend_Db_Expr('NOW()'); 
            unset($data['id']);
            return $this->getDbTable()->insert($data);
        }
        else
        {
            $this->getDbTable()->update($data, array('id = ?' => $id));
            return TRUE;
        }
    }
    
    public function delete(Application_Model_Resourcegroup $resGrp)
    {
        $data = array(
            'status' => 'D'
        );

        if (null === ($id = $resGrp->getId()) || $id == 0)
        {
            throw new Exception("Invalid Id");
        }
        else
        {
            $this->getDbTable()->update($data, array('id = ?' => $id));
            return TRUE;
        }
    }

}
