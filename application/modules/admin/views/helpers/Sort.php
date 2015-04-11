<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Admin_Views_Helpers_Sort extends Zend_View_Helper_Abstract
{
    public function sort($field, $params=array())
    {    
        $str = '';
        $str .= '<a href="'.$this->view->url(array_merge($params,array("orderBy" => $field, "orderByType" => "ASC"))).'">';
            $str .= '<i class="fa fa-arrow-up" style="color:#cccccc"></i>';
        $str .= '</a>';
        $str .= '<a href="'.$this->view->url(array_merge($params,array("orderBy" => $field, "orderByType" => "DESC"))).'">';
            $str .= '<i class="fa fa-arrow-down" style="color:#cccccc"></i>';
        $str .= '</a>';
                 
        return  $str;
    }
}