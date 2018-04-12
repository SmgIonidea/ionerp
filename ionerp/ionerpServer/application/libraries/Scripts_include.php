<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Script_manage
 *
 * @author Himansu
 */
class Scripts_include {

    public $jsFile = array('admin', 'ionerp_layout');
    public $cssFile = array('admin', 'ionerp_layout');
    private $__jsFiles = array(
        'ionerp_layout' => array(
            'top' => array(
                //'js_css/angular_2/js', Angular JS files should be included here
                '/assets/js_css/jquery/js/jquery.js',
                '/assets/js_css/jquery/js/jquery-ui.js',
                '/assets/js_css/jquery/js/jquery.noty.js',
                '/assets/js_css/jquery/js/jquery.multiselect.js',
                '/assets/js_css/jquery/js/jquery.validate.js',
                '/assets/js_css/bootstrap/js/bootstrap.js',
                '/assets/js_css/custom/js/ionerp.js',
                '/assets/js_css/dataTables/js/dataTables.bootstrap.js',
                '/assets/js_css/dataTables/js/jquery.dataTables.js',
            ),
            'common' => array(
                '/assetsjs_css/custom/js/rbac/rbac.js',
            ),
        ),
    );
    private $__cssFiles = array(
        'ionerp_layout' => array(
            'common' => array(
                '/assets/js_css/bootstrap/css/bootstrap.css',
                '/assets/js_css/bootstrap/css/bootstrap_icon.css',
                '/assets/js_css/bootstrap/css/bootstrap-theme.css',
                '/assets/js_css/bootstrap/css/animate.min.css',
                '/assets/js_css/jquery/css/jquery-ui.css',
                '/assets/js_css/jquery/css/jquery-ui.theme.css',
                '/assets/js_css/jquery/css/jquery.noty.css',
                '/assets/js_css/jquery/css/noty_theme_default.css',
                '/assets/js_css/jquery/css/jquery.multiselect.css',
                '/assets/js_css/custom/css/custom.css',
                '/assets/js_css/custom/css/_all-skins.css',
                '/assets/js_css/custom/css/ionerp.css',
                '/assets/js_css/font-awesome/css/font-awesome.css',
                '/assets/js_css/dataTables/css/dataTables.bootstrap.css',
                '/assets/js_css/dataTables/css/dataTables.material.css',
                '/assets/js_css/dataTables/css/jquery.dataTables.css',
            ),
        ),
    );

    function __construct() {
        
    }

    public function includePlugins($plugins = null, $type = null) {
        if (is_array($plugins) && $type == 'css') {
            $this->cssFile = array_merge($this->cssFile, $plugins);
        } elseif (is_array($plugins) && $type == 'js') {
            $this->jsFile = array_merge($this->jsFile, $plugins);
        } else {
            $this->cssFile = array_merge($this->cssFile, $plugins);
            $this->jsFile = array_merge($this->jsFile, $plugins);
        }
    }

    public function includeCss($layout) {
        // pma($layout,1);
        $str = '';
        if (is_array($this->cssFile)) {
            foreach ($this->cssFile as $pluginName) {
                if (array_key_exists($pluginName, $this->__cssFiles)) {

                    //inlclude common files
                    foreach ($this->__cssFiles[$layout]['common'] as $key => $files) {
                        if (is_array($files)) {
                            foreach ($files as $scripts) {
                                $str.=$scripts;
                            }
                        } else {
                            $str.='<link rel="stylesheet" href="' . base_url($files) . '" />';
                        }
                    }

//                    foreach ($this->__cssFiles[$pluginName] as $files) {
//                        $str.='<link rel="stylesheet" href="' . base_url($files) . '" />';
//                    }
                }
            }
        }
        return $str;
    }

    public function includeJs($layout) {
        $str = '';

        if (is_array($this->jsFile)) {
            foreach ($this->jsFile as $key => $pluginName) {
                if (array_key_exists($pluginName, $this->__jsFiles)) {
                    //inlclude common files
                    foreach ($this->__jsFiles[$layout]['common'] as $key => $files) {
                        if (is_array($files)) {
                            foreach ($files as $scripts) {
                                $str.=$scripts;
                            }
                        } else {
                            $str.='<script src="' . base_url($files) . '" ></script>';
                        }
                    }

//                    foreach ($this->__jsFiles[$pluginName] as $key => $files) {
//                        if (is_array($files)) {
//                            foreach ($files as $scripts) {
//                                $str.=$scripts;
//                            }
//                        } else {
//                            $str.='<script src="' . base_url($files) . '" ></script>';
//                        }
//                    }
                }
            }
        }
        return $str;
    }

    public function preJs($layout = NULL) {
        // pma($layout,1);
        $str = '';
        if (isset($this->__jsFiles[$layout])) {
            foreach ($this->__jsFiles[$layout]['top'] as $files) {
                $str.='<script src="' . base_url($files) . '" ></script>';
            }
            foreach ($this->__jsFiles[$layout]['common'] as $files) {
                $str.='<script src="' . base_url($files) . '" ></script>';
            }
        }


        return $str;
    }

}
