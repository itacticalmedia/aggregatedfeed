<?php

class Application_Model_FeedDataTempMapper extends Application_Model_MapperBase
{
    /**
     * Set table Name
     * @var string
     * 
     */
    protected $_name = 'feedDataTemp';

    /**
     *
     * @param Application_Model_FeedData $feeddata
     * @param type $by
     * @return type
     */
    public function save(Application_Model_FeedDataTemp $feeddata)
    {


        if (null === ($id = $feeddata->getId()))
        {
            $data = array(
                'feedId' => $feeddata->getFeedId(),
                'title' => $feeddata->getTitle(),
                'link' => $feeddata->getLink(),
                'description' => $feeddata->getDescription(),
                'enclsr_url' => $feeddata->getEncloserUrl(),
                'enclsr_length' => $feeddata->getEncloserLength(),
                'enclsr_type' => $feeddata->getEncloserType(),
                'data' => $feeddata->getData(),
                'publishDate' => $feeddata->getPublishDate()               
            );

            unset($data['id']);
            return $this->getDbTable()->insert($data);
        }
        else
        {


            $data = array(
                'title' => $feeddata->getTitle(),
                'description' => $feeddata->getDescription(),
                'enclsr_url' => $feeddata->getEncloserUrl(),
                'enclsr_length' => $feeddata->getEncloserLength(),
                'enclsr_type' => $feeddata->getEncloserType(),
                'data' => $feeddata->getData(),
                'publishDate' => $feeddata->getPublishDate()
            );

            $this->getDbTable()->update($data, array('id = ?' => $id));
            return $id;
        }
    }
    
    /**
     * If exist return the ID else FALSE
     * @param Application_Model_FeedData $feeddata
     * @return int|boolean
     */
    public function isExist(Application_Model_FeedDataTemp $feeddata)
    {
        $table = $this->getDbTable();

        $select = $table->select()
                ->from(array($this->getTableName()), array("id"))
                ->where("feedId = ?", $feeddata->getFeedId())
                ->where("link = ?", $feeddata->getLink());

        $row = $table->fetchRow($select);

        if ($row)
        {
            return $row['id'];
        }
        else
        {
            return FALSE;
        }
    }

    /**    
     * @return Application_Model_FeedDataTemp[] | boolean
     */
    public function loadAllOrderByDate()
    {        
        $select = $this->getDbTable()->select()->order("publishDate ASC"); 
        return $this->paginator($select);
    }
    
    
    public function deleteAll()
    {
         $table = $this->getDbTable();
         $where = $table->getAdapter()->quoteInto('0 = 0');
         $table->delete($where);
    }

}