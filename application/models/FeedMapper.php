<?php

class Application_Model_FeedMapper extends Application_Model_MapperBase
{

    /**
     * Set table Name
     * @var string
     * 
     */
    protected $_name = 'feed';

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

    /**
     * 
     * @param Application_Model_Feed $feed
     * @param type $by
     * @return int saved id
     */
    public function save(Application_Model_Feed $feed, $by = 0)
    {
        $data = array(
            'feedName' => $feed->getFeedName(),
            'feedUrl' => $feed->getFeedUrl(),
            'itemTag' => $feed->getItemTag(),
            'feedPriority' => $this->getMaxOrdered() + 1
        );

        if (null === ($id = $feed->getId()))
        {
            $data['modifiedOn'] = new Zend_Db_Expr('NOW()');
            $data['createdBy'] = $by;
            unset($data['id']);
            return $this->getDbTable()->insert($data);
        }
        else
        {
            $data['modifiedOn'] = new Zend_Db_Expr('NOW()');
            $data['modifiedBy'] = $by;
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

    public function getMaxOrdered()
    {
        $table = $this->getDbTable();
        $sel = $table->select()
            ->from(
            array($this->getTableName())
            , 'MAX(IFNULL(feedPriority,0)) maxOrd'
        );

        $row = $table->fetchRow($sel);
        return $row->maxOrd;
    }

    public function makeOrder($id, $oldIndex, $newIndex)
    {
        $dba = Zend_Db_Table_Abstract::getDefaultAdapter();
        if ($oldIndex < $newIndex)
        {
            $qm = "UPDATE feed set feedPriority = ? where id = ?";
            $dba->query($qm, array($newIndex, $id));

            $q = "UPDATE feed set feedPriority = feedPriority-1 where feedPriority<=? and feedPriority>=? and id!=? and feedPriority>1";

            $dba->query($q, array($newIndex,$oldIndex,$id));
        }
        if ($oldIndex > $newIndex)
        {
            $maxInd = $this->getMaxOrdered();
            
            $qm = "UPDATE feed set feedPriority = ? where id = ?";
            $dba->query($qm, array($newIndex, $id));

            $q = "UPDATE feed set feedPriority = feedPriority+1 where feedPriority>=? and feedPriority<=? and id!=? and feedPriority < ?";

            $dba->query($q, array($newIndex,$oldIndex,$id,$maxInd));
            
            //die;
        }
    }

}
