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
       
        $feed = new Zend_Feed_Writer_Feed;
        $feed->setTitle('Where Hip Hop Lives');
        $feed->setDescription('Where Hip Hop Lives News');
        $feed->setLink('http://www.wherehiphoplives.com');
        $feed->setLanguage('en');        

        if ($feeds)
        {

            foreach ($feeds as $fd)
            {


                $entry = $feed->createEntry();

                $entry->setTitle($fd->getTitle());
                $entry->setDescription(($fd->getDescription() != "") ? $fd->getDescription() : ' ');
                $entry->setLink($fd->getLink());
                $entry->setContent($fd->getData());
                $entry->setDateCreated($fd->_ut);
                $entry->addCategories(array(array('term' => $fd->_feedName)));
                
                if($fd->getEncloserUrl() != '' && $fd->getEncloserLength() != '' && $fd->getEncloserType() != '')
                {
                    $enclosure = array('uri' => $fd->getEncloserUrl(), 'length' => $fd->getEncloserLength(), 'type' => $fd->getEncloserType());
                    $entry->setEnclosure($enclosure);
                }               
                
                
                $feed->addEntry($entry);
            }
        }


        header('Content-type: text/xml');

        echo $feed->export('rss');
    }

    public function showfeedbypositionAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $mp = new Application_Model_FeedDataMapper();
        $feeds = $mp->loadAllOrderByPosition();

        $feed = new Zend_Feed_Writer_Feed;
        $feed->setTitle('Featured on Where Hip Hop Lives');
        $feed->setDescription('Where Hip Hop Lives Featured News');
        $feed->setLink('http://www.wherehiphoplives.com');
        $feed->setLanguage('en');    

        if ($feeds)
        {

            foreach ($feeds as $fd)
            {


                $entry = $feed->createEntry();

                $entry->setTitle($fd->getTitle());
                $entry->setDescription(($fd->getDescription() != "") ? $fd->getDescription() : ' ');
                $entry->setLink($fd->getLink());
                $entry->setContent($fd->getData());
                $entry->setDateCreated($fd->_ut);
                $entry->addCategories(array(array('term' => $fd->_feedName)));
                
                if($fd->getEncloserUrl() != '' && $fd->getEncloserLength() != '' && $fd->getEncloserType() != '')
                {
                    $enclosure = array('uri' => $fd->getEncloserUrl(), 'length' => $fd->getEncloserLength(), 'type' => $fd->getEncloserType());
                    $entry->setEnclosure($enclosure);
                }   
                
                $feed->addEntry($entry);
            }
        }


        header('Content-type: text/xml');

        echo $feed->export('rss');
    }

    public function testfeedAction()
    {
        /* $feed = Zend_Feed_Reader::import('http://www.power106.com/feeds/article/');
          // print_r($feed);
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

          print_r($data);
          die; */
    }

}
