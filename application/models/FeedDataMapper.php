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
    
    public function delete(Application_Model_FeedData $feeddata)
    {
        $table = $this->getDbTable();
        $where = $table->getAdapter()->quoteInto('id = ?', $feeddata->getId());
        return $table->delete($where);

    }
    
   
}
