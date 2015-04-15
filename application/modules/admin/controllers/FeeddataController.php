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
            if(empty($r->_id))
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
        $search = array(array("col" => "feedId", "value" => $id));
        $data = $map->loadAll(FALSE, 0, array(), $search);

        $this->view->feeddata = $data;
        
    }

   
    public function feeddatareorderAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $request = $this->request->getParams();
        if ($this->request->isPost())
        {
           

            if (count($msg) > 0)
            {
                $this->_helper->flashMessenger->addMessage(array("error" => $msg));
            }
            $this->gotoPage('feedadd', 'index');
        }
    }

   

}