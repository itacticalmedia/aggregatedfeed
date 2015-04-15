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
                $select->orWhere($searchAr['col'] . ' ' . $op . ' ', $searchAr['value']);
            }
        }
        if (count($orderBy) > 0)
        {
            $select->order($orderBy['col'] . " " . $orderBy['type']);
        }
     
        $res = $this->paginator($select, $page, $limit);
        return $res;
    }

}
