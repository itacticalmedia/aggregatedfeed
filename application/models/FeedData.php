<?php

class Application_Model_FeedData extends Application_Model_Base
{

    const VIEWED = 1;
    const NOT_VIEWED = 0;    
    const HIDE = 1;
    const NOT_HIDE = 0;
    
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

   
    public function delete()
    {
        $m = new Application_Model_FeedDataMapper();
        return $m->delete($this);
    }
    
    /**
     * If exist return the ID else FALSE
     * @param Application_Model_FeedData $feeddata
     * @return int|boolean
     */    
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

    /**
     * @param type $v
     * @return type
     */
    public function setViewed($v)
    {
        $this->_viewed = $v;
        return $this;
    }

    /**
     * @return type
     */
    public function getViewed()
    {
        return $this->_viewed;
    }   
        
    /**
     * @param type $v
     * @return type
     */
    public function setHide($v)
    {
        $this->_hide = $v;
        return $this;
    }

    /**
     * @return type
     */
    public function getHide()
    {
        return $this->_hide;
    }
    
    public function saveFeedData(Application_Model_FeedData $feeddata)
    {
        $m = new Application_Model_FeedDataMapper();
        return $m->save($feeddata);
    }
    
    public function refreshFeed()
    {
        $tempmp = new Application_Model_FeedDataTempMapper();
        $feeds = $tempmp->loadAllOrderByDate();
                
        if($feeds)
        {
            $mp1 = new Application_Model_FeedDataMapper();            
            $totalRecord = $mp1->getMaxOrdered();
            $totalRecord = ceil($totalRecord);
            
            foreach ($feeds as $feed)
            {
                $fdata = new Application_Model_FeedData();                
                
                $fdata->setFeedId($feed->getFeedId());
                $fdata->setTitle($feed->getTitle());
                $fdata->setDescription($feed->getDescription());
                $fdata->setLink($feed->getLink());
                $fdata->setData($feed->getData());  
                $fdata->setNewPosition(++$totalRecord);
                $fdata->setViewed(1);
                $fdata->setEncloserUrl($feed->getEncloserUrl());
                $fdata->setEncloserLength($feed->getEncloserLength());
                $fdata->setEncloserType($feed->getEncloserType());                                              
                $fdata->setPublishDate($feed->getPublishDate());                
                    
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
            
            $tempmp->deleteAll();
        }
    }
    
    /**
     * This function hide feed data if already hide then unhide     
     * @return int
     */
    public function toggleHide()
    {
        $mp = new Application_Model_FeedDataMapper();
        return $mp->toggleHide($this);
    }

}
