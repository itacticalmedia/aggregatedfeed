<?php

class Application_Model_Feed extends Application_Model_Base
{

    public function setId($a)
    {
        $this->_id = $a;
        return $this;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setFeedName($a)
    {
        $this->_feedName = $a;
        return $this;
    }

    public function getFeedName()
    {
        return $this->_feedName;
    }

    public function setFeedUrl($a)
    {
        $this->_feedUrl = $a;
        return $this;
    }

    public function getFeedUrl()
    {
        return $this->_feedUrl;
    }

    public function setItemTag($a)
    {
        $this->_itemTag = $a;
        return $this;
    }

    public function getItemTag()
    {
        return $this->_itemTag;
    }

    public function setFeedPriority($a)
    {
        $this->_feedPriority = $a;
        return $this;
    }

    public function getFeedPriority()
    {
        return $this->_feedPriority;
    }

    
    public function setCreatedOn($a)
    {
        $this->_createdOn = $a;
        return $this;
    }

    public function getCreatedOn()
    {
        return $this->_createdOn;
    }

    public function setCreatedBy($a)
    {
        $this->_createdBy = $a;
        return $this;
    }

    public function getCreatedBy()
    {
        return $this->_createdBy;
    }
    
    
    
    public function setModifiedOn($a)
    {
        $this->_modifiedOn = $a;
        return $this;
    }

    public function getModifiedOn()
    {
        return $this->_modifiedOn;
    }

    public function setModifiedBy($a)
    {
        $this->_modifiedBy = $a;
        return $this;
    }

    public function getModifiedBy()
    {
        return $this->_modifiedBy;
    }
    
    
    public function save($by=0)
    {
        $m = new Application_Model_FeedMapper();
        return $m->save($this,$by);
    }

    public function delete()
    {
        $m = new Application_Model_FeedMapper();
        $m->delete($this);
        
        $md  = new Application_Model_FeedDataMapper();
        $md->deleteByFeed($this);
    }
    
    public function saveFeedData(Application_Model_FeedData $feeddata)
    {
        $feeddata->setFeedId($this->getId());
        $m = new Application_Model_FeedDataMapper();
        return $m->save($feeddata);
    }
    
    /**
     * Return array of feeds
     * @return array
     */
    public function getFeed()
    {
        $data = array();

        try
        {
            if ($this->getFeedUrl() != '')
            {
                $feed = Zend_Feed_Reader::import($this->getFeedUrl());

                foreach ($feed as $entry)
                {
                    $data[] = array(
                        'title' => $entry->getTitle(),
                        'description' => $entry->getDescription(),
                        'dateModified' => $entry->getDateModified(),
                        'authors' => $entry->getAuthors(),
                        'link' => $entry->getLink(),
                        'content' => $entry->getContent()
                    );
                }
            }
        }
        catch (Exception $ex)
        {
            throw new Exception("Error Parsing URL ".$this->getFeedUrl()." :: ".$ex->getMessage());
        }

        return $data;
    }

    public function insertFeedData()
    {
        $feed = $this->getFeed();

        if (is_array($feed) && count($feed) > 0)
        {
            $mp = new Application_Model_FeedDataMapper();            
            $totalRecord = $mp->getMaxOrdered();
            
            foreach ($feed as $entry)
            {
                $fdata = new Application_Model_FeedData();
                
                $fdata->setFeedId($this->getId());
                $fdata->setTitle($entry['title']);
                $fdata->setDescription($entry['description']);
                $fdata->setLink($entry['link']);
                $fdata->setData($entry['content']);               
                $fdata->setNewPosition(++$totalRecord);
                $fdata->setViewed(0);
                
                $dtModfied = $entry['dateModified'];                
                if ($dtModfied instanceof Zend_Date)
                {
                    $fdata->setPublishDate($dtModfied->getIso());
                }
                    
                if (FALSE ===($feedDataId = $fdata->isExist()) )
                {   
                    $fdata->setId(NULL);
                }
                else
                {
                    $fdata->setId($feedDataId);
                }
                
                $this->saveFeedData($fdata);
                unset($fdata);
            }
        }
    }

}
