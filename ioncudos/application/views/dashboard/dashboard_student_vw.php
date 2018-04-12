<!--head here -->
<?php $this->load->view('includes/head'); ?>
<!--branding here-->
<?php $this->load->view('includes/branding'); ?>
<!-- Navbar here -->
<?php $this->load->view('includes/navbar'); ?> 
<div id="loading" class="ui-widget-overlay ui-front">
    <img src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
</div>
<div class="container-fluid-custom">
    <div class="row-fluid">
        <!--sidenav.php-->
        <div class="span12">
            <!-- Contents -->
            <section id="contents">
                <div class="bs-docs-example fixed-height">
                    <div class="row-fluid">
                        <!--content goes here-->	
                        <div class="navbar">
                            <div class="navbar-inner-custom">
                                Dashboard
                            </div>
                        </div>	
                        <div class="tabbable"> <!-- Only required for left/right tabs -->
                            <ul class="nav nav-tabs">                                
                                <li class="active"><a href="#tab1" data-toggle="tab">My Actions</a></li>
                            </ul>
                        </div>
                        <div id="data_div">
                            
                        </div>
                        <!--Do not place contents below this line-->
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
<!---place js.php here -->
<?php $this->load->view('includes/js'); ?>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/custom/dashboard/dashboard_student.js'); ?>"></script>

</body>
</html>