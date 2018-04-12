<?php

/*
 * Ion ERP common view template. This template can be used as reference template during the IonERP development
 * Author: Mritunjay B S
 * Date: 1/8/2017
 * IonIdea Inc Pvt Ltd. Hubli.
 */
?>

<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?= $this->layout->title ?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <?= $this->scripts_include->preJs('ionerp_layout') ?>
        <?= $this->scripts_include->includeCss('ionerp_layout') ?>
        <script>
            var base_url = '<?= APP_BASE ?>';
        </script>
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">
            <?php
                if ($this->layout->headerFlag):
                    $this->load->view($this->layout->layoutsFolder . '/header');
                else:
                    $this->load->view($this->layout->layoutsFolder . '/no_header');
                endif;
            ?>
            <!-- Left side column. contains the logo and sidebar -->
            <?php $this->load->view($this->layout->layoutsFolder . '/l_side_bar_1'); ?>
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
            </div>
                <?php
                if ($this->layout->footerFlag) {
                    $this->load->view($this->layout->layoutsFolder . '/footer');
                } else {
                    $this->load->view($this->layout->layoutsFolder . '/no_footer');
                }
                ?> 
                <!-- Control Sidebar -->
                <?php $this->load->view($this->layout->layoutsFolder . '/r_side_bar'); ?>
        </div>
                <!-- ./wrapper -->
                <?= $this->scripts_include->includeJs('ionerp_layout') ?>
        <script>
            $.widget.bridge('uibutton', $.ui.button);
        </script>
    </body>
</html>

