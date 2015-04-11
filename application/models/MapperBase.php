<?php

/**
 * This Class is a mapper base class 
 */
class Application_Model_MapperBase
{

    /**
     * Hold DB table object
     * @var Application_Model_DbTable_DbBase 
     */
    protected $_dbTable;

    /**
     * Hold Table Name
     * @var string
     */
    protected $_name;

    /**
     * This function set the DB table object
     * @param string $dbTable
     * @return Application_Model_MapperBase
     * @throws Exception 
     */
    public function setDbTable($dbTable)
    {
        if (is_string($dbTable))
        {
            $dbTable = new $dbTable(array('name' => $this->_name));
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract)
        {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->_dbTable = $dbTable;
        return $this;
    }

    /**
     * This function return the DB table object
     * @return Application_Model_DbTable_Base
     */
    public function getDbTable()
    {
        if (null === $this->_dbTable)
        {
            $this->setDbTable('Application_Model_DbTable_DbBase');
        }
        return $this->_dbTable;
    }

    /**
     * 
     * @return Zend_Db_Adapter_Abstrac
     */
    public function getDb()
    {
        return $this->getDbTable()->getAdapter();
    }

    /**
     * This function load the rows using the primary key
     * @param type $id
     * @return boolean|Zend_Db_Table_Row_Abstract
     */
    public function find($id)
    {
        $resultSet = $this->getDbTable()->find($id);
        if (0 == count($resultSet))
        {
            return FALSE;
        }
        $row = $resultSet->current();
        return $row;
    }
    
     public function fetchAllWithStatus($statusArray = array())
    {
        if(count($statusArray) == 0)
        {
            return $this->fetchAll();
        }else
        {
            $table = $this->getDbTable();

            $sel = $table->select()
                    ->where('status IN (?)', $statusArray);

         
            $resultSet = $table->fetchAll($sel);

            return $this->createModelObject($resultSet);
        }
        
    }
    

    public function createModelObject($resultSet)
    {
        $model_classname = $this->getModelName();
        if (0 == count($resultSet))
        {
            return FALSE;
        }
        foreach ($resultSet as $row)
        {
            $to = new $model_classname(0);
            $this->initObject($row, $to);
            $entries[] = $to;
        }
        return $entries;
    }

    /**
     * This function initailize all the database value to
     * model properties
     * @param Zend_Db_Table_Row_Abstract $row
     * @param Application_Model_Base $Obj
     * @return Application_Model_Base 
     */
    public function initObject($row, Application_Model_Base &$Obj)
    {

        if ($row)
        {
            if (count($row) > 0)
            {
                foreach ($row as $k => $v)
                {
                    $att = "_$k";
                    $Obj->$att = $v;
                }
            }
        }
        return $Obj;
    }

    /**
     * 
     * @param Zend_Db_Select $select
     * @param mixed $page
     * @param mixed $limit
     * @param string $adapterType  "DBSELECT"
     * @return FALSE|Application_Model_Base[]
     */
    public function paginator(Zend_Db_Select $select, $page = FALSE, $limit = 0, $adapterType = "")
    {
        if ($page)
        {
            if ($adapterType == "DBSELECT")
            {
                $adapter = new Zend_Paginator_Adapter_DbSelect($select);
            }
            else
            {
                $adapter = new Zend_Paginator_Adapter_DbTableSelect($select);
            }

            $paginator = new Zend_Paginator($adapter);
            $paginator->setCurrentPageNumber((int) $page);
            $paginator->setItemCountPerPage($limit);
            $entries = $this->createModelObject($paginator);
            $res['paginator'] = $paginator;
            $res['data'] = $entries;
        }
        else
        {
            if ($adapterType == "DBSELECT")
            {
                $resultSet = $this->getDbTable()->getDefaultAdapter()->fetchAll($select);
            }
            else
            {
                $resultSet = $this->getDbTable()->fetchAll($select);
            }

            $entries = $this->createModelObject($resultSet);
            $res = $entries;
        }

        return $res;
    }

    public function getModelName()
    {
        $me = get_class($this);
        $he = str_replace('Mapper', '', $me);
        return $he;
    }

    public final function getTableName()
    {
        return $this->_name;
    }

    /**
     * 
     * @return boolean|\array of Application_Model_Base
     * 
     */
    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries = array();
        $model_classname = $this->getModelName();
        if (0 == count($resultSet))
        {
            return FALSE;
        }

        foreach ($resultSet as $row)
        {
            $to = new $model_classname(0);
            $this->initObject($row, $to);
            $entries[] = $to;
        }

        return $entries;
    }

}
