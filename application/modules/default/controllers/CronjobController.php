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
        Application_Model_Helpers_Common::doDebug();
        
        $fdMp = new Application_Model_FeedMapper();
        $feedMasters = $fdMp->loadAll();

        if ($feedMasters)
        {
            $allFeedsArray = array();
            
            foreach ($feedMasters as $feedMaster)
            {
                try
                {
                    $feedId = $feedMaster->getId();
                    $feeds = $feedMaster->getFeed();
                    
                    if (is_array($feeds) && count($feeds) > 0)
                    {
                        $allFeedsArray = array_merge($allFeedsArray, $feeds);     
                    }
                    
                    unset($feeds);
                }
                catch (Exception $ex)
                {
                    Application_Model_Helpers_Common::debugprint("Error when reading RSS from cron FeedID(".$feedId."): " 
                            . $ex->getMessage());        
                    
                    $body = "Error to get feed for master feed id = {$feedId} <br>";
                    $body.="Error Message:". $ex->getMessage();
                    
                    
                    Application_Model_Helpers_Mail::send(Application_Model_Helpers_Mail::getStoreFromMail(), Application_Model_Helpers_Mail::getStoreFromName(), "Error in get feed", $body, "omid@itacticalmedia.com");
                }
            }            
            
            usort($allFeedsArray, "Application_Model_FeedData::sortFeedData"); 
            Application_Model_FeedData::insertFeedData($allFeedsArray);            
            unset($allFeedsArray);            
        }
    }

}
