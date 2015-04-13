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
    
    
    public function save()
    {
        $m = new Application_Model_FeedMapper();
        return $m->save($this);
    }

    public function delete()
    {
        $m = new Application_Model_FeedMapper();
        return $m->delete($this);
    }
    
    public function saveFeedData(Application_Model_FeedData $feeddata)
    {
        $feeddata->setFeedId($this->getId());
        $m = new Application_Model_FeedDataMapper();
        return $m->save($feeddata);
    }

}
