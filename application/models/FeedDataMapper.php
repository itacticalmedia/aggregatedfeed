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
        $data = array(
            'feedId' => $feeddata->getFeedId(),            
            'title' => $feeddata->getTitle(),
            'link' => $feeddata->getLink(),
            'description' => $feeddata->getDescription(),
            'data' => $feeddata->getData(),
            'publishDate' => $feeddata->getPublishDate(),
            'originalPosition' => $feeddata->getOriginalPosition(),
            'newPosition' => $feeddata->getNewPosition()
        );
        
        if (null === ($id = $feeddata->getId()))
        {
            unset($data['id']);
            return $this->getDbTable()->insert($data);
        }
        else
        {
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
     * 
     * @param Application_Model_FeedData $feeddata
     * @return boolean
     */
    public function isExist(Application_Model_FeedData $feeddata)
    {
       $table = $this->getDbTable();

        $select = $table->select()
            ->from(array($this->getTableName()), array("totalRecords" => "count(1)"))
            ->where("feedId = ?", $feeddata->getFeedId())
            ->where("link = ?", $feeddata->getLink());
                
        $row = $table->fetchRow($select);
        
        if($row['totalRecords'] > 0)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
    
   
}
