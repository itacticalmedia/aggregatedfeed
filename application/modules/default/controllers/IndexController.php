<?php

class IndexController extends Plugin_Inject
{

    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
             
    }
    
    
    public function showfeedbydateAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        
        $mp = new Application_Model_FeedDataMapper();
        $feeds = $mp->loadAllOrderByDate();
        
        $feedData = array();        
        $feedData['title'] = 'Emmis Communications test';
        $feedData['description'] = 'great media. great people. great service.';
        $feedData['link'] = 'http://www.emmis.com';
        $feedData['charset'] = 'utf8';
    

        if($feeds)
        {            
            foreach ($feeds as $feed)
            {
                $feedData['entries'][] = array(
                                'title' => $feed->getTitle(),
                                'description' => ($feed->getDescription() != "")?$feed->getDescription():'',
                                'link' => $feed->getLink(),
                                'content' => $feed->getData(),
                                'pubDate' => $feed->getPublishDate()
                            );
            }
        }       
        
     
        // create our feed object and import the data
        $feed = Zend_Feed::importArray ($feedData, 'rss' );
        // set the Content Type of the document
        header ( 'Content-type: text/xml' );
        // echo the contents of the RSS xml document
        echo $feed->send();        
        
    }
    
    
    public function showfeedbypositionAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        
        $mp = new Application_Model_FeedDataMapper();
        $feeds = $mp->loadAllOrderByPosition();
        
        $feedData = array();        
        $feedData['title'] = 'Emmis Communications';
        $feedData['description'] = 'great media. great people. great service.';
        $feedData['link'] = 'http://www.emmis.com';
        $feedData['charset'] = 'utf8';
    

        if($feeds)
        {            
            foreach ($feeds as $feed)
            {
                $feedData['entries'][] = array(
                                'title' => $feed->getTitle(),
                                'description' => ($feed->getDescription() != "")?$feed->getDescription():'',
                                'link' => $feed->getLink(),
                                'content' => $feed->getData(),
                                'pubDate' => $feed->getPublishDate()
                            );
            }
        }      
        
     
        // create our feed object and import the data
        $feed = Zend_Feed::importArray ($feedData, 'rss' );
        // set the Content Type of the document
        header ( 'Content-type: text/xml' );
        // echo the contents of the RSS xml document
        echo $feed->send();        
        
    }

}
