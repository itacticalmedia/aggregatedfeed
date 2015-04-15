<?php

class Admin_IndexController extends Plugin_Inject
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
        $map = new Application_Model_FeedMapper();
        $this->view->feeds = $map->loadAll(FALSE, 0, array('col' => 'feedPriority', 'type' => 'ASC'));
    }

    public function feedaddAction()
    {
        
    }

    public function feedsaveAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $request = $this->request->getParams();
        if ($this->request->isPost())
        {
            $msg = array();

            if ($request["feedName"] == "")
            {
                $msg[] = "Please enter Name.";
            }
            if ($request["feedUrl"] == "")
            {
                $msg[] = "Please enter url.";
            }
            if ($request["itemTag"] == "")
            {
                $msg[] = "Please select item Tag.";
            }


            if (count($msg) == 0)
            {

                $feedName = $request["feedName"];
                $feedUrl = $request["feedUrl"];
                $itemTag = $request["itemTag"];


                $id = $request["id"];


                if ($id > 0)
                {
                    $r = new Application_Model_Feed($id);
                }
                else
                {
                    $r = new Application_Model_Feed();
                    $r->setId(NULL);
                }
                $r->setFeedName($feedName);
                $r->setFeedUrl($feedUrl);
                $r->setItemTag($itemTag);

                $res = $r->save();

                if ($res > 0)
                {
                    $Succmsg[] = "Feed \"$feedName\" has been saved successfully.";
                    $this->_helper->flashMessenger->addMessage(array("success" => $Succmsg));
                    $this->gotoPage('index', 'index');
                }
                else
                {
                    $msg[] = "Error on feed save.";
                }
            }


            if (count($msg) > 0)
            {
                $this->_helper->flashMessenger->addMessage(array("error" => $msg));
            }
            $this->gotoPage('feedadd', 'index');
        }
    }

    public function feededitAction()
    {
        $id = $this->request->getParam('id');
        if ($id > 0)
        {
            $r = new Application_Model_Feed($id);
            $this->view->id = $r->getId();
            $this->view->feedName = $r->getFeedName();
            $this->view->feedUrl = $r->getFeedUrl();
            $this->view->itemTag = $r->getItemTag();
            $this->view->feedPriority = $r->getFeedPriority();
            $this->render('feedadd');
        }
        else
        {
            throw new Exception("Invalid request");
        }
    }

    public function feeddeleteAction()
    {
        $id = $this->request->getParam('id');
        if ($id > 0)
        {
            $r = new Application_Model_Feed($id);
            $rname = $r->getFeedName();
            $r->delete();

            $Succmsg[] = "Feed \"$rname\" delete successfully.";
            $this->_helper->flashMessenger->addMessage(array("success" => $Succmsg));
            $this->gotoPage('index', 'index');
        }
        else
        {
            throw new Exception("Invalid request");
        }
    }
    

    public function feedorderAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $request = $this->request->getParams();

        $msg = array();
        $id = $request["id"];
        $oldIndex = $request["oldIndex"];
        $newIndex = $request["newIndex"];
       
        $m = new Application_Model_FeedMapper();
        $m->makeOrder($id,$oldIndex, $newIndex);
        $this->gotoPage('index', 'index');
    }

}
