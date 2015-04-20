<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class CronjobController extends Plugin_Inject
{

    public function init()
    {
        parent::init();
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
    }

    public function indexAction()
    {
       
    }

    public function insfeedAction()
    {
        //Application_Model_Helpers_Common::doDebug();
        
        $fdMp = new Application_Model_FeedMapper();
        $feedMasters = $fdMp->loadAll();

        if ($feedMasters)
        {
            foreach ($feedMasters as $feedMaster)
            {
                try
                {
                    $feedMaster->insertFeedData();
                  #  echo "finished inserting::" . $feedMaster->getId() . "." . $feedMaster->getFeedUrl();
                }
                catch (Exception $ex)
                {
                    Application_Model_Helpers_Common::debugprint("Error when reading RSS from cron: " 
                            . $ex->getMessage());                  
                }
            }
        }
    }

}
