<?php

/**
 * This is a helper class for making Menu
 */
class Admin_Views_Helpers_AdminMenu extends Zend_View_Helper_Abstract
{

    function hasApplicableChild($pages)
    {
        if (!empty($pages))
        {
            foreach ($pages as $page)
            {
                if ($this->view->navigation()->accept($page))
                {
                    return TRUE;
                }
            }

            return FALSE;
        }
        return TRUE;
    }
    
    
    private function helperSubMenu($pages)
    {
        
        $html = array();
        
        foreach ($pages as $subpage)
        {
            $subStr = '';
            if ($this->view->navigation()->accept($subpage) && $this->hasApplicableChild($subpage->pages)  && !in_array($subpage->getId(), $this->getInvisibleMenus()))
            {
                $hasSubCls = '';
               
                if ($subpage->isActive(true))
                {
                    $hasSubCls = ' class = "hover" ';
                }


                $html[] = '<li '.$hasSubCls.'>';
                $href = $subpage->getHref();
                $hrfUrl = '';
                if ($href)
                {
                    $hrfUrl = 'href="' . $href . '"';
                }
                
                $html[] = "<a " . $hrfUrl . ">";
                $html[] = $subpage->getLabel();
                $html[] = "</a>";

                if (!empty($subpage->pages) && $this->hasApplicableChild($subpage->pages))
                {
                    $subStr = $this->helperSubMenu($subpage->pages);
                    
                    if($subStr != '')
                    {
                        $html[] = "     <ul>";
                        $html[] = $subStr;
                        $html[] = "     </ul>";
                    }
                }

                $html[] = "</li>";
            }
        }

        if (count($html) > 0)
        {
            return implode(PHP_EOL, $html);
        }
        else
        {
            return '';
        }
    }

    /**
     * This helper function create menu
     * @param Zend_Navigation $container
     * @return string
     */
    public function AdminMenu(Zend_Navigation $container)
    {
        $html = array();

        $html[] = '<ul class="dropdown">';

        $hasAnyPages = 0;
        foreach ($container as $page)
        {
            //print_r($page);
            if ($this->view->navigation()->accept($page) && $this->hasApplicableChild($page->pages))
            {

                $hasSubCls = '';

                if ($page->isActive(true))
                {
                    $hasSubCls = ' class = "hover" ';
                }


                $parStr = '';
                if (!empty($page->pages) && $this->hasApplicableChild($page->pages))
                {
                    $parStr = $this->helperSubMenu($page->pages);

                }

                if ($parStr != '')
                {
                    $html[] = '<li '.$hasSubCls.'>';
                    $html[] = '<a href="#">'. $page->getLabel().'</a>';
                    $html[] = "     <ul class='sub_menu'>";
                    $html[] = $parStr;
                    $html[] = "     </ul>";

                    $html[] = "</li>";
                }
            }
        }

        $html[] = '</ul>';

        return implode(PHP_EOL, $html);
    }
    
    
    function getInvisibleMenus()
    {
        //return array();
        return array('resourcegroupedit','roleedit','useredit');
    }

}
