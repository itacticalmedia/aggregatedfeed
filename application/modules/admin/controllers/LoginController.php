<?php

class Admin_LoginController extends Plugin_Inject
{

    public function init()
    {
        parent::init();
        $this->view->sessionNameSpace = $this->sessionNameSpace = Zend_Registry::get('SESS_admin');       
        
        
    }

    public function indexAction()
    {
        $this->view->title = "Login";
        if ($this->_helper->flashMessenger->hasMessages())
        {
            $this->view->errors = $this->_helper->flashMessenger->getMessages();
        }

        if (isset($_COOKIE['rememberadmin_me_key']) && $_COOKIE['rememberadmin_me_key'] != "")
        {
            $adminuser = new Application_Model_User($_COOKIE['rememberadmin_me_key']);
            $this->sessionNameSpace->user = $adminuser;

            if (isset($this->sessionNameSpace->user_redirect) && $this->sessionNameSpace->user_redirect != '')
            {
                $this->_redirect($this->getRequest()->getScheme() . '://' . $this->getRequest()->getHttpHost() . $this->sessionNameSpace->user_redirect);
            }
            else
            {
                $this->gotoPage('index', 'index');
            }

            exit;
        }
        
        
        if (isset($this->sessionNameSpace->user))
        {
            $this->gotoPage('index', 'index');  
        }
    }

    public function authAction()
    {
        $er = array();
        
        $loginForm = new Application_Form_Auth_Login();

        if ($loginForm->isValid($_POST))
        {
            $username = $loginForm->getValue('username');
            $password = $loginForm->getValue('password');

            $admUserObj = new Application_Model_User();
            $adminuser = $admUserObj->authenticate($username, Application_Model_Helpers_Common::encryptPassword($password));

            if ($adminuser)
            {
                if ($_POST['remember_me'] == "on")
                {
                    $remember_me_key = $adminuser->getId();
                    setcookie('rememberadmin_me_key', $remember_me_key, time() + (3600 * 24 * 92), '/');
                }

                $this->sessionNameSpace->user = $adminuser;
                if (isset($this->sessionNameSpace->user_redirect) && $this->sessionNameSpace->user_redirect != '')
                {
                    $this->_redirect($this->getRequest()->getScheme() . '://' . $this->getRequest()->getHttpHost() . $this->sessionNameSpace->user_redirect);
                }
                else
                {

                    $this->gotoPage('index', 'index');
                }

                exit;
            }
            else
            {
                $er[] = "Invalid Username or Password";
                $this->_helper->flashMessenger->addMessage(array('error' => $er));
                $this->gotoPage('index');
            }
        }
        else
        {
            $errors = $loginForm->getErrors();
            if (($errors['username'][0]))
            {
                $er[] = "Username can't be empty.";
            }
            if (($errors['password'][0]))
            {
                $er[] = "Password can't be empty.";
            }
            $this->_helper->flashMessenger->addMessage(array('error' => $er));
            $this->gotoPage('index');
        }
    }


    public function logoutAction()
    {
        $this->_helper->viewRenderer->setNoRender(TRUE);
        setcookie('rememberadmin_me_key', 0, time() - 3600, '/');
        Zend_Session::namespaceUnset($this->sessionNameSpace->getNamespace());
        $this->gotoPage('index');
    }

}
