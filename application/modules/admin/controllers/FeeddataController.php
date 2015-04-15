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
        $data = $map->loadAll(FALSE, 0, array("col" => "newPosition", "type" => "asc"), $search);

        $this->view->feeddata = $data;
    }

    public function reorderfeedAction()
    {
       
        $map = new Application_Model_FeedDataMapper();
        $search = array(
            array("col" => "viewed", "value" => 1)
        );
        $data = $map->loadAll(FALSE, 0, array("col" => "newPosition", "type" => "asc"), $search);

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
        $oldIndex = $request["oldIndex"];
        $newIndex = $request["newIndex"];
       
        $m = new Application_Model_FeedDataMapper();
        $m->makeOrder($id,$oldIndex, $newIndex);
        $this->gotoPage('reorderfeed', 'feeddata');
    }

}
