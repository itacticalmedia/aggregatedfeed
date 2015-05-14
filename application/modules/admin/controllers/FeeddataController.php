<?php

class Admin_FeeddataController extends Plugin_Inject
{

    public function init()
    {
        parent::init();
        $this->sessionNameSpace = $this->view->sessionNameSpace = Zend_Registry::get('SESS_admin');
        if (!isset($this->sessionNameSpace->user))
        {
            $this->sessionNameSpace->user_redirect = $this->getRequest()->getRequestUri();

            $this->gotoPage('index', 'login');
        }
    }

    public function indexAction()
    {
        $id = $this->request->getParam('feedid');


        if ($id > 0)
        {
            $r = new Application_Model_Feed($id);
            if (empty($r->_id))
            {
                throw new Exception("Bad data");
            }
            $this->view->feedid = $r->getId();
            $this->view->feedName = $r->getFeedName();
            $this->view->feedUrl = $r->getFeedUrl();
            $this->view->itemTag = $r->getItemTag();
        }
        else
        {
            throw new Exception("Invalid request");
        }
        //array('col' => 'feedPriority', 'type' => 'ASC')
        $map = new Application_Model_FeedDataMapper();
        $search = array(
            array("col" => "feedId", "value" => $id),
            array("col" => "viewed", "value" => 1)
        );


        $data = $map->loadAll(FALSE, 0, array("col" => "newPosition", "type" => "desc"), $search);



        $this->view->feeddata = $data;
    }

    public function reorderfeedAction()
    {
        $fromdate = $this->request->getParam('fromdate');
        $todate = $this->request->getParam('todate');

        if (empty($fromdate) || $fromdate == "")
        {
            $fromdate = Application_Model_Helpers_Common::currentDateMySql();
        }
        if (empty($todate) || $todate == "")
        {
            $todate = Application_Model_Helpers_Common::currentDateMySql();
        }

        $map = new Application_Model_FeedDataMapper();
        $search = array(
            array("col" => "viewed", "value" => 1)
           
        );
        $data = $map->loadAll(FALSE, 0, array("col" => "newPosition", "type" => "desc"), $search, $fromdate, $todate);

        $this->view->fromdate = $fromdate;
        $this->view->todate = $todate;
        $this->view->feeddata = $data;
    }

    public function refreshfeedAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);


        $map = new Application_Model_FeedDataMapper();
        $map->refreshFeed();

        $Succmsg[] = "Feed has been refreshed successfully.";
        $this->_helper->flashMessenger->addMessage(array("success" => $Succmsg));

        $this->gotoPage('reorderfeed', 'feeddata', 'admin');
    }
    
    public function feeddataorderAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $request = $this->request->getParams();

        $msg = array();
        $id = $request["id"];
        $betweenFromId = $request["betweenFromId"];
        $betweenToId = $request["betweenToId"];
        $fromdate = $request["fromdate"];
       
        $m = new Application_Model_FeedDataMapper();
        $m->makeOrder($id,$betweenFromId, $betweenToId);
        //$this->gotoPage('reorderfeed', 'feeddata',  $this->moduleName,array("?fromdate"=>$fromdate));
        $this->_redirect(BASE_URL_ADMIN . '/feeddata/reorderfeed/?fromdate='.$fromdate);
    }

    
    public function togglehideAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $request = $this->request->getParams();
        $id = $request["id"];
        $fromdate = $request["fromdate"];
        
        if($id <= 0)
        {
            throw new Exception("Id not found");            
        }
        
        $fd = new Application_Model_FeedData($id);
        $fd->toggleHide(); 
        
        if(isset($request["feedid"]))
        {
            $this->_redirect(BASE_URL_ADMIN . '/feeddata/index/feedid/'.$request["feedid"]);            
        }
        else
        {
            $this->_redirect(BASE_URL_ADMIN . '/feeddata/reorderfeed/?fromdate='.$fromdate);
        }
        
        die;
    }
}
