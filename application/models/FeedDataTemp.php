<?php

class Application_Model_FeedDataTemp extends Application_Model_Base
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

   
    
    public function setTitle($a)
    {
        $this->_title = $a;
        return $this;
    }
    
    
    public function setEncloserUrl($a)
    {
        $this->_enclsr_url = $a;
        return $this;
    }

    public function getEncloserUrl()
    {
        return $this->_enclsr_url;
    }
    
    
    public function setEncloserLength($a)
    {
        $this->_enclsr_length = $a;
        return $this;
    }

    public function getEncloserLength()
    {
        return $this->_enclsr_length;
    }
    
    public function setEncloserType($a)
    {
        $this->_enclsr_type = $a;
        return $this;
    }

    public function getEncloserType()
    {
        return $this->_enclsr_type;
    }
   
    /**
     * If exist return the ID else FALSE
     * @param Application_Model_FeedData $feeddata
     * @return int|boolean
     */    
    public function isExist()
    {
        $mp = new Application_Model_FeedDataTempMapper();
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
