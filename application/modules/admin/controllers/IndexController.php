<?php

class Admin_IndexController extends Plugin_Inject
{

    public function init()
    {
        parent::init();
        $this->sessionNameSpace  = $this->view->sessionNameSpace= Zend_Registry::get('SESS_admin');
        if (!isset($this->sessionNameSpace->user))
        {
            $this->sessionNameSpace->user_redirect = $this->getRequest()->getRequestUri();

            $this->gotoPage('index', 'login');
        }
    }

    public function indexAction()
    {
        
    }

}
