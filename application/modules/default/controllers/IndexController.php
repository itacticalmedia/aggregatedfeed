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
        $feed->setTitle('Emmis Communications');
        $feed->setDescription('great media. great people. great service.');
        $feed->setLink('http://www.emmis.com');


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
        $feed->setTitle('Emmis Communications');
        $feed->setDescription('great media. great people. great service.');
        $feed->setLink('http://www.emmis.com');


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
