<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?= $this->layout->title ?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <?= $this->scripts_include->preJs($this->layout->layout) ?> <!-- To load the Page PRE JS files -->
        <?= $this->scripts_include->includeCss($this->layout->layout) ?> <!-- To load the Page Common CSS files -->
        <script>
            var base_url = '<?= APP_BASE ?>';
        </script>
    </head>
    <?php ///$this->load->view($this->layout->layoutsFolder . '/top_bar'); ?>
    <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">
            <?php
            if ($this->layout->headerFlag) :
                $this->load->view($this->layout->layoutsFolder . '/header');
            else :
                $this->load->view($this->layout->layoutsFolder . '/no_header');
            endif;
            ?>
            <!-- Left side column. contains the Logged in user image and sidebar -->
            <?php $this->load->view($this->layout->layoutsFolder . '/sidenav'); ?>
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="headline"> <!-- Div for Page Headings -->
                        <div class="heading">
                            <?php
                            if ($this->layout->navTitleFlag) {
                                $this->layout->navTitle = 'Title';
                                echo $this->layout->navTitle;
                            }
                            ?>
                        </div>                    
                    </div> <!-- Page Headings Div Ends Here -->          
                    <?php
                    if ($this->layout->breadcrumbsFlag) {
                        echo $this->breadcrumbs->show();
                    }
                    ?>
                </section>
                <section class="content-message">                    
                    <?php if ($this->session->flashdata('success')): ?>                        

                        <div class="col-sm-12">
                            <span class="label label-success"><?= $this->session->flashdata('success'); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if ($this->session->flashdata('error')): ?> 
                        <div class="col-sm-12">
                            <span class="label label-danger"><?= $this->session->flashdata('error'); ?></span>                    
                        </div>
                    <?php endif; ?>
                    <?php if ($this->session->flashdata('warning')): ?> 
                        <div class="col-sm-12">
                            <span class="label label-danger"><?= $this->session->flashdata('warning'); ?></span>                    
                        </div>
                    <?php endif; ?>
                    <?php if ($this->session->flashdata('info')): ?> 
                        <div class="col-sm-12">
                            <span class="label label-danger"><?= $this->session->flashdata('info'); ?></span>                    
                        </div>
                    <?php endif; ?>
                </section>
                <!-- Main content -->
                <section class="content">
                    <?php
                    if (GLOBAL_NOTICE) {
                        echo '<br/><br/><div class="alert alert-warning alert-dismissible">
                               <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                               <i class="icon fa fa-warning"></i> Alert!' . GLOBAL_NOTICE . '</div>';
                    }
                    ?>
                    <?php $this->load->view($this->layout->layoutsFolder . '/popups'); ?>
                    <?= $content ?>
                </section>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->
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
        <?= $this->scripts_include->includeJs($this->layout->layout) ?>
        <script>
            $.widget.bridge('uibutton', $.ui.button);
        </script>
    </body>
</html>
