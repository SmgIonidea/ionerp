<?php

/*
 * @Author: Himansu S
 * @Desc: Implement Layout mechanism in codeigniter
 * @Create Date:26th Mar 2015
 * @Last Update:Himansu S
 * @Updated on:03rd Mar 2016
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Layout
{
    //set the data to be passed to view
    public $data = array();
    //set the view name to be loaded
    public $view = null;
    //set the view path
    public $viewFolder = null;
    //set the layout name
    public $layout = 'default';
    //set the layout path
    public $layoutsFodler = 'layout';
    //set the title
    public $title = '';
    //set navigator title
    public $navTitle = '';
    public $mainMenu = true;
    public $noHeader = false;
    public $navBarTitle;
    public $navBarTitleFlag=true;
    var $obj;

    function __construct() {
        $this->obj = & get_instance();
    }

    /*
     * @method:setLayout
     * @Param:
     *      string $layoutFolder 
     *      string $layout 
     * @desc: Set the custom layout path and layout name
     */

    function setLayout($layoutFolder = null, $layout = null) {
        if ($layoutFolder) {
            $this->layoutsFodler = $layoutFolder;
        }
        if ($layout) {
            $this->layout = $layout;
        }
    }

    /*
     * @method:setView
     * @Param:
     *      string $viewFolder 
     *      string $view 
     * @desc: Set the custom view path and view name
     */

    function setView($viewFolder = null, $view = null) {
        if ($viewFolder) {
            $this->viewFolder = $viewFolder;
        }
        if ($view) {
            $this->view = $view;
        }
    }

    /*
     * @method:render
     * @Param:NA
     * @desc: Render the view
     */

    function render($param = null) {
	
        $controller = $this->obj->router->fetch_class();
        $method = $this->obj->router->fetch_method();
        $viewFolder = !($this->viewFolder) ? $controller : $this->viewFolder . '/' . $controller;
        $view = !($this->view) ? $method : $this->view;
        $loadView = $viewFolder . '/' . $view;
        $loadLayout = $this->layoutsFodler . '/' . $this->layout;
        $loadedData = array();
        if (is_array($param)) {
            if (array_key_exists('view', $param)) {
                $loadView = $param['view'];
            }
            if (array_key_exists('data', $param)) {
                $this->data = $param['data'];
            }
            if (array_key_exists('layout', $param)) {
                $loadLayout = $param['layout'];
            }
        }        
        //echo 'load view'.$loadView;exit;
        $loadedData['content'] = $this->obj->load->view($loadView, $this->data, true);
        $loadedData['data'] = $this->data;
        $this->obj->load->view($loadLayout, $loadedData);
    }
}