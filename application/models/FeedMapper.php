<?php

class Application_Model_FeedMapper extends Application_Model_MapperBase
{
    /**
     * Set table Name
     * @var string
     * 
     */
    protected $_name = 'feed';
    
    public function loadAll($page=FALSE, $limit = Application_Model_Helpers_Common::MAX_RECORDS_PER_PAGE, $orderBy =array(),$search=array())
    {
        $select = $this->getDbTable()->select();
        
        if(count($search)>0)
        {
            foreach ($search as $searchAr)
            {
                $op = ' = ?';
                if(isset($searchAr['op']) && $searchAr['op']!='')
                {
                    $op = $searchAr['op'];
                }
                $select->orWhere($searchAr['col'].' ' .$op.' ', $searchAr['value']);
            }
            
        }
        if(count($orderBy)>0)
        {
            $select->order($orderBy['col']." ".$orderBy['type']);
        }
        $res = $this->paginator($select, $page, $limit);
        return $res;
    }
    
    
    /**
     * 
     * @param Application_Model_Feed $feed
     * @param type $by
     * @return int saved id
     */
    public function save(Application_Model_Feed $feed,$by=0)
    {
        $data = array(
            'feedName' => $feed->getFeedName(),
            'feedUrl' => $feed->getFeedUrl(),
            'itemTag' => $feed->getItemTag(),
            'feedPriority' => $feed->getFeedPriority()
        );

        if (null === ($id = $feed->getId()))
        {
            $data['createdBy']= $by;
            unset($data['id']);
            return $this->getDbTable()->insert($data);
        }
        else
        {
            $data['modifieDate']= new Zend_Db_Expr('NOW()');
            $data['modifieBy']= $by;
            $this->getDbTable()->update($data, array('id = ?' => $id));
            return $id;
        }
    }
    public function delete(Application_Model_Feed $feed)
    {
        $table = $this->getDbTable();
        $where = $table->getAdapter()->quoteInto('id = ?', $feed->getId());
        return $table->delete($where);

    }
    
   
}
