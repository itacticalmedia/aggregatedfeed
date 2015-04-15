<?php

class Application_Model_FeedData extends Application_Model_Base
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
    
    public function setFeedId($a)
    {
        $this->_feedId = $a;
        return $this;
    }

    public function getFeedId()
    {
        return $this->_feedId;
    }

    /**
     * @param type $v
     * @return type
     */
    public function setTitle($v)
    {
        $this->_title = $v;
        return $this;
    }

    /**
     * @return type
     */
    public function getTitle()
    {
        return $this->_title;
    }


    public function setData($a)
    {
        $this->_data = $a;
        return $this;
    }

    public function getData()
    {
        return $this->_data;
    }

    public function setPublishDate($a)
    {
        $this->_publishDate = $a;
        return $this;
    }

    public function getPublishDate()
    {
        return $this->_publishDate;
    }

    public function setOriginalPosition($a)
    {
        $this->_originalPosition = $a;
        return $this;
    }

    public function getOriginalPosition()
    {
        return $this->_originalPosition;
    }

    public function setNewPosition($a)
    {
        $this->_newPosition = $a;
        return $this;
    }

    public function getNewPosition()
    {
        return $this->_newPosition;
    }

    
    public function setTitle($a)
    {
        $this->_title = $a;
        return $this;
    }

    public function getTitle()
    {
        return $this->_title;
    }
    
    public function setDescription($a)
    {
        $this->_description = $a;
        return $this;
    }

    public function getDescription()
    {
        return $this->_description;
    }
    
    public function setLink($a)
    {
        $this->_link = $a;
        return $this;
    }

    public function getLink()
    {
        return $this->_link;
    }
    
    public function delete()
    {
        $m = new Application_Model_FeedDataMapper();
        return $m->delete($this);
    }
    
    public function isExist()
    {
        $mp = new Application_Model_FeedDataMapper();
        return $mp->isExist($this);
    }

    /**
     * @param type $v
     * @return type
     */
    public function setLink($v)
    {
        $this->_link = $v;
        return $this;
    }

    /**
     * @return type
     */
    public function getLink()
    {
        return $this->_link;
    }


    /**
     * @param type $v
     * @return type
     */
    public function setDescription($v)
    {
        $this->_description = $v;
        return $this;
    }

    /**
     * @return type
     */
    public function getDescription()
    {
        return $this->_description;
    }

}
