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

        if ($row)
        {
            return $row['id'];
        }
        else
        {
            return FALSE;
        }
    }

    public function loadAll($page = FALSE, $limit = Application_Model_Helpers_Common::MAX_RECORDS_PER_PAGE, $orderBy = array(), $search = array(), $fromdate = "", $todate = "")
    {
        /*
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


          if ($fromdate != "")
          {
          $select->where("date(publishDate) >=?", $fromdate);
          }
          if ($todate != "")
          {
          $select->where("date(publishDate) <=?", $todate);
          }
          }

          if (count($orderBy) > 0)
          {
          $select->order($orderBy['col'] . " " . $orderBy['type']);
          }

         */



        $db = $this->getDbTable()->getDefaultAdapter();
        $select = $db->select()
                ->from(array("fd" => $this->getTableName()))
                ->joinInner(array("f" => "feed"), "f.id = fd.feedId", array('f.feedName'));
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


            if ($fromdate != "")
            {
                $select->where("date(publishDate) >=?", $fromdate);
            }
            if ($todate != "")
            {
                $select->where("date(publishDate) <=?", $todate);
            }
        }

        if (count($orderBy) > 0)
        {
            $select->order($orderBy['col'] . " " . $orderBy['type']);
        }
       


        $res =  $this->paginator($select, FALSE, 0, "DBSELECT");
        return $res;
    }

    /**
     * 
     * @return Application_Model_FeedData[] | array | boolean
     */
    public function loadAllOrderByDate()
    {
        $db = $this->getDbTable()->getDefaultAdapter();
        $select = $db->select()
                ->from(array("fd" => $this->getTableName()), array("*", "UNIX_TIMESTAMP(publishDate) ut"))
                ->joinInner(array("f" => "feed"), "f.id = fd.feedId", array('f.feedName'))
                ->where("fd.viewed = ?", Application_Model_FeedData::VIEWED)
                ->order(array("fd.publishDate DESC", "f.feedPriority"));
       
        return $this->paginator($select, FALSE, 0, "DBSELECT");
    }

    /**
     * 
     * @return Application_Model_FeedData[] | array | boolean
     */
    public function loadAllOrderByPosition()
    {       
        $db = $this->getDbTable()->getDefaultAdapter();
        $select = $db->select()
                ->from(array("fd" => $this->getTableName()), array("*", "UNIX_TIMESTAMP(publishDate) ut"))
                ->joinInner(array("f" => "feed"), "f.id = fd.feedId", array('f.feedName'))
                ->where("fd.viewed = ?", Application_Model_FeedData::VIEWED)
                ->order("newPosition DESC");
       
        return $this->paginator($select, FALSE, 0, "DBSELECT");
    }

    public function deleteByFeed(Application_Model_Feed $feed)
    {
        $table = $this->getDbTable();
        $where = $table->getAdapter()->quoteInto('feedId = ?', $feed->getId());
        return $table->delete($where);
    }

    public function refreshFeed()
    {
        $data = array(
            'viewed' => 1
        );

        return $this->getDbTable()->update($data, array('viewed = ?' => 0));
    }

    public function getMaxOrdered()
    {
        $table = $this->getDbTable();
        $sel = $table->select()
                ->from(
                array($this->getTableName())
                , 'IFNULL(MAX(IFNULL(newPosition,0)),0) maxOrd'
        );

        $row = $table->fetchRow($sel);
        return $row->maxOrd;
    }

    public function getMinOrdered()
    {
        $table = $this->getDbTable();
        $sel = $table->select()
                ->from(
                array($this->getTableName())
                , 'IFNULL(MIN(IFNULL(newPosition,0)),0) minOrd'
        );

        $row = $table->fetchRow($sel);
        return $row->minOrd;
    }

    public function getNextOrdered($whichId)
    {
        $table = $this->getDbTable();

        $sel = $table->select()
                ->from(
                        array($this->getTableName())
                        , 'newPosition'
                )
                ->where("id = ?", $whichId);
        $row = $table->fetchRow($sel);
        $whichOrder = $row->newPosition;

        if ($this->getMinOrdered() == $whichOrder)
        {
            return 0;
        }
        $sel = $table->select()
                        ->from(
                                array($this->getTableName())
                                , 'id'
                        )
                        ->where("newPosition < ?", $whichOrder)
                        ->order('newPosition DESC')->limit(0, 1);
        $row = $table->fetchRow($sel);
        if (!$row)
        {
            return 0;
        }
        return $row->id;
    }

    public function getPrevOrdered($whichId)
    {
        $table = $this->getDbTable();

        $sel = $table->select()
                ->from(
                        array($this->getTableName())
                        , 'newPosition'
                )
                ->where("id = ?", $whichId);
        $row = $table->fetchRow($sel);
        $whichOrder = $row->newPosition;

        if ($this->getMaxOrdered() == $whichOrder)
        {
            return 0;
        }

        $sel = $table->select()
                        ->from(
                                array($this->getTableName())
                                , 'id'
                        )
                        ->where("newPosition > ?", $whichOrder)
                        ->order('newPosition DESC')->limit(0, 1);
        $row = $table->fetchRow($sel);
        if (!$row)
        {
            return 0;
        }
        return $row->id;
    }

    public function makeOrder($id, $betweenFromId, $betweenToId)
    {
        Application_Model_Helpers_Common::debugprint("UP:: previd" . $betweenFromId . " nextid:" . $betweenToId);
        $dba = Zend_Db_Table_Abstract::getDefaultAdapter();

        if ($betweenToId == 0) //get next ordered if drop to last
        {
            $betweenToId = $this->getNextOrdered($betweenFromId);
        }
        if ($betweenFromId == 0) //get next ordered if drop to last
        {
            $betweenFromId = $this->getPrevOrdered($betweenToId);
        }
        $prevOrder = 0;
        $nextOrder = 0;

        $table = $this->getDbTable();
        $sel = $table->select()
                ->from(
                        array($this->getTableName())
                        , 'newPosition'
                )
                ->where("id  = ?", $betweenFromId);
        $row = $table->fetchRow($sel);
        if ($row)
        {
            $prevOrder = $row->newPosition;
        }

        $sel = $table->select()
                ->from(
                        array($this->getTableName())
                        , 'newPosition'
                )
                ->where("id  = ?", $betweenToId);
        $row = $table->fetchRow($sel);
        if ($row)
        {
            $nextOrder = $row->newPosition;
        }

        Application_Model_Helpers_Common::debugprint("UP:: prev" . $prevOrder . " next:" . $nextOrder);

        if ($prevOrder == 0)
        {
            $prevOrder = ($nextOrder + 1);
        }
        $newOrder = ($prevOrder + $nextOrder) / 2;

        $q = "UPDATE feedData set newPosition = $newOrder where id=$id";

        Application_Model_Helpers_Common::debugprint("UP::" . $q);
        $dba->query($q);
    }

}