<?php

class Application_Model_FeedDataMapper extends Application_Model_MapperBase
{
    /**
     * Set table Name
     * @var string
     * 
     */
    protected $_name = 'feedData';
    
   /**
    * 
    * @param Application_Model_FeedData $feeddata
    * @param type $by
    * @return type
    */
    public function save(Application_Model_FeedData $feeddata)
    {
        
        
        if (null === ($id = $feeddata->getId()))
        {
            $data = array(
                'feedId' => $feeddata->getFeedId(),            
                'title' => $feeddata->getTitle(),
                'link' => $feeddata->getLink(),
                'description' => $feeddata->getDescription(),
                'data' => $feeddata->getData(),
                'publishDate' => $feeddata->getPublishDate(),
                'originalPosition' => $feeddata->getOriginalPosition(),
                'newPosition' => $feeddata->getNewPosition(),
                'viewed' => $feeddata->getViewed()
            );
            
            unset($data['id']);
            return $this->getDbTable()->insert($data);
        }
        else
        {
            
            
            $data = array(                       
                'title' => $feeddata->getTitle(),               
                'description' => $feeddata->getDescription(),
                'data' => $feeddata->getData(),
                'publishDate' => $feeddata->getPublishDate()                
            );
            
            $this->getDbTable()->update($data, array('id = ?' => $id));
            return $id;
        }
    }
    
    /**
     * 
     * @param Application_Model_FeedData $feeddata
     * @return int
     */
    public function delete(Application_Model_FeedData $feeddata)
    {
        $table = $this->getDbTable();
        $where = $table->getAdapter()->quoteInto('id = ?', $feeddata->getId());
        return $table->delete($where);

    }
    
    /**
     * If exist return the ID else FALSE
     * @param Application_Model_FeedData $feeddata
     * @return int|boolean
     */
    public function isExist(Application_Model_FeedData $feeddata)
    {
       $table = $this->getDbTable();

        $select = $table->select()
            ->from(array($this->getTableName()), array("id"))
            ->where("feedId = ?", $feeddata->getFeedId())
            ->where("link = ?", $feeddata->getLink());
                
        $row = $table->fetchRow($select);
        
        if($row)
        {
            return $row['id'];
        }
        else
        {
            return FALSE;
        }
    }
    
   

    public function loadAll($page = FALSE, $limit = Application_Model_Helpers_Common::MAX_RECORDS_PER_PAGE, $orderBy = array(), $search = array())
    {
        $select = $this->getDbTable()->select();

        if (count($search) > 0)
        {
            foreach ($search as $searchAr)
            {
                $op = ' = ?';
                if (isset($searchAr['op']) && $searchAr['op'] != '')
                {
                    $op = $searchAr['op'];
                }
                $select->Where($searchAr['col'] . ' ' . $op . ' ', $searchAr['value']);
            }
        }
        if (count($orderBy) > 0)
        {
            $select->order($orderBy['col'] . " " . $orderBy['type']);
        }

        $res = $this->paginator($select, $page, $limit);
        return $res;
    }

    public function refreshFeed($feedId)
    {
        $data = array(
            'viewed' => 1
        );

        return $this->getDbTable()->update($data, array('viewed = ?' => 0, "feedId=?" => $feedId));
    }

    public function getMaxOrdered()
    {
        $table = $this->getDbTable();
        $sel = $table->select()
                ->from(
                array($this->getTableName())
                , 'MAX(IFNULL(newPosition,0)) maxOrd'
        );

        $row = $table->fetchRow($sel);
        return $row->maxOrd;
    }

    public function makeOrder($id, $oldIndex, $newIndex)
    {
        $dba = Zend_Db_Table_Abstract::getDefaultAdapter();
        if ($oldIndex < $newIndex)
        {
            $qm = "UPDATE feedData set newPosition = ? where id = ?";
            $dba->query($qm, array($newIndex, $id));

            $q = "UPDATE feedData set newPosition = newPosition-1 where newPosition<=? and newPosition>=? and id!=? and newPosition>1";

            $dba->query($q, array($newIndex, $oldIndex, $id));
        }
        if ($oldIndex > $newIndex)
        {
            $maxInd = $this->getMaxOrdered();

            $qm = "UPDATE feedData set newPosition = ? where id = ?";
            $dba->query($qm, array($newIndex, $id));

            $q = "UPDATE feedData set newPosition = newPosition+1 where newPosition>=? and newPosition<=? and id!=? and newPosition < ?";

            $dba->query($q, array($newIndex, $oldIndex, $id, $maxInd));

            //die;
        }
    }

}
