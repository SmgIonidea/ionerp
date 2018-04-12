<?php
/*
--------------------------------------------------------------------------------------------------------------------------------
* Description	: Program Edit Static view Page.	  
* Modification History:
* Date				Modified By				Description
* 20-08-2013                    Mritunjay B S                           Added file headers, function headers & comments. 
---------------------------------------------------------------------------------------------------------------------------------
*/
?>

    <!--head here -->
    <?php $this->load->view('includes/head'); ?>
    <body data-spy="scroll" data-target=".bs-docs-sidebar" onload="fun_title1()">
        <!--branding here-->
        <?php $this->load->view('includes/branding'); ?>

        <!-- Navbar here -->
        <?php $this->load->view('includes/navbar'); ?> 
        <div class="container-fluid">
            <div class="row-fluid">
                <!--sidenav.php-->
                <?php $this->load->view('includes/static_sidenav_1'); ?>
                <div class="span10">
                    <!-- Contents
                    ================================================== -->
                    <section id="contents">
                        <div class="bs-docs-example">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Program Details
                                </div>
                            </div>	
                            
                            <form class="form-horizontal" method="POST" id="program_form" name="program_form" action="<?php echo base_url('configuration/program/program_edit') . '/' . $pgm_id; ?>" >
                                <div class="row-fluid">
                                    <div class="span12">
                                        <div class="row-fluid">
                                            <!-- Span6 starts here-->
                                            <div class="span6">
                                                <?php foreach ($program_data as $pgm_data): ?>
                                                    <div class="control-group">
                                                        <label class="control-label inline" for="inputProgramType">Program Type: </label>
                                                        <div class="controls">	
														<?php
                                                                foreach ($pgm_type_data as $pgmtype):
                                                                    if ($pgmtype['pgmtype_id'] == $selected_pgmtype)
                                                                        echo $pgmtype['pgmtype_name'];
                                                             
                                                                endforeach;
                                                                ?>	
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label inline" for="inputMode">Mode: </label>
                                                        <div class="controls">
                                                                <?php
                                                                foreach ($pgm_mode_data as $mode_name):
                                                                    if ($mode_name['mode_id'] == $selected_mode)
                                                                        echo $mode_name['mode_name'];
                                                                endforeach;
                                                                ?>	
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label inline" for="inputSpecialization" >Specialization: </label>
                                                        <div class="controls">
                                                            <?php echo $program_data[0]['pgm_specialization']; ?> 
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label inline" for="inputAcronym">Specialization Acronym: </label>
                                                        <div class="controls">
                                                            <?php echo $program_data[0]['pgm_spec_acronym']; ?>
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label inline" for="inputDepartment">Department: </label>
                                                        <div class="controls">
                                                                <?php
                                                                foreach ($pgm_dept_data as $dept_name):
                                                                    if ($dept_name['dept_id'] == $selected_dept)
                                                                        echo $dept_name['dept_name'];
                                                                endforeach;
                                                                ?>		
                                                            
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label inline" for="inputNoOfTerms">Number of Terms: </label>
                                                        <div class="controls">
                                                            <?php echo $program_data[0]['total_terms']; ?>
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label inline" for="inputTotalCredits">Total No. of Credits: </label>
                                                        <div class="controls">
                                                            <?php echo $program_data[0]['total_credits']; ?>
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label inline" for="inputitle">Program Title:</label>
                                                        <div class="controls">
                                                            <?php echo $program_data[0]['pgm_title']; ?>
                                                            
                                                        </div>
                                                    </div>		  
                                                    <div class="control-group">
                                                        <label class="control-label inline" for="inputShortName">Program Acronym: </label>
                                                        <div class="controls">
                                                            <?php echo $program_data[0]['pgm_acronym']; ?> 
                                                            
                                                        </div>
                                                    </div>
                                                </div> <!-- Ends here-->
                                                <!-- Span6 starts here-->	
                                                <div class="span6">
                                                    <div class="control-group">
                                                        <label class="control-label inline" for="inputMaximumDuration">Maximum Duration:</label>
                                                        <div class="controls">
                                                            <?php echo $program_data[0]['pgm_max_duration']; ?>
                                                           
                                                                <?php
                                                                foreach ($pgm_unit_max_data as $unit_name):
                                                                    if ($unit_name['unit_id'] == $selected_pgm_max_duration)
                                                                        echo $unit_name['unit_name'];
                                                                endforeach;
                                                                ?>		
                                                           
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label inline" for="inputMinimumDuration">Minimum Duration: </label>
                                                        <div class="controls">
                                                            <?php echo $program_data[0]['pgm_min_duration']; ?>	
                                                            
                                                                <?php
                                                                foreach ($pgm_unit_min_data as $unit_name):
                                                                    if ($unit_name['unit_id'] == $selected_pgm_min_duration)
                                                                        echo $unit_name['unit_name'];
                                                                endforeach;
                                                                ?>		
                                                            
                                                        </div>	
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label inline" for="inputNoOfTerms">Term Max Credits: </label>
                                                        <div class="controls">
                                                            <?php echo $program_data[0]['term_max_credits']; ?>
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label inline" for="inputNoOfTerms">Term Min Credits: </label>
                                                        <div class="controls">
                                                            <?php echo $program_data[0]['term_min_credits']; ?>
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label inline" for="inputMinimumDuration">Term Max Duration: </label>
                                                        <div class="controls">
                                                            <?php echo $program_data[0]['term_max_duration']; ?>
                                                            
                                                                <?php
                                                                foreach ($pgm_unit_max_data as $unit_name):
                                                                    if ($unit_name['unit_id'] == $selected_term_max)
                                                                        echo $unit_name['unit_name'];
                                                                   
                                                                endforeach;
                                                                ?>				
                                                        </div>	
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label inline" for="inputMinimumDuration">Term Min Duration: </label>
                                                        <div class="controls">
                                                            <?php echo $program_data[0]['term_min_duration']; ?>	
                                                            
                                                                <?php
                                                                foreach ($pgm_unit_min_data as $unit_name):
                                                                    if ($unit_name['unit_id'] == $selected_term_min)
                                                                        echo $unit_name['unit_name'];
                                                                endforeach;
                                                                ?>		
                                                           
                                                        </div>	
                                                    </div>
                                                </div><!--End of span6-->
                                            </div> <!--End of row-fluid-->
                                        </div><!--End of span12-->
                                    </div>
                                    <div class="pull-right">
                                        <a href="<?php echo base_url('configuration/program/static_index'); ?>"><b class="btn btn-danger"><i class="icon-remove icon-white"></i><span></span> close </b></a>
                                    </div><br>
                                <?php endforeach; ?> 
                                <input id="short"   name="pgm_id" type="hidden" value="<?php echo $pgm_id; ?>"  size="20" >
                            </form>
                        </div>
                    </section>
                </div>		
            </div>
        </div>
        <!---place footer.php here -->
        <?php $this->load->view('includes/footer'); ?> 
        <!---place js.php here -->
        <?php $this->load->view('includes/js'); ?>
    </body>
</html>
<link rel="stylesheet" href="/resources/demos/style.css" />
<script type="text/javascript">

        $(document).ready(function() {
    /*
     * Function is to validate the program add form. It checks against special chracters(# @ ' "  . - etc).
     * @param - -----------.
     * returns the error message if any of the special characters appears.
     */

            $("#program_form").validate({
                rules: {
                    spec: {
                        maxlength: 50,
                    },
                    type: {
                        maxlength: 20,
                        required: true
                    },
                    mode: {
                        maxlength: 10,
                        required: true
                    },
                    acro: {
                        maxlength: 50,
                        required: true
                    },
                    department: {
                        maxlength: 20,
                        required: true
                    },
                    credits: {
                        maxlength: 3,
                        max: 250,
                        required: true
                    },
                    title: {
                        maxlength: 100,
                        required: true
                    },
                    short: {
                        maxlength: 20,
                        required: true
                    },
                    terms: {
                        maxlength: 2,
                        required: true
                    },
                    max: {
                        maxlength: 2,
                        required: true
                    },
                    min: {
                        maxlength: 2,
                        required: true
                    },
                    Termmin: {
                        maxlength: 2,
                        required: true
                    },
                    Termmax: {
                        maxlength: 2,
                        required: true
                    },
                    terms1: {
                        maxlength: 2,
                        required: true
                    },
                    terms2: {
                        maxlength: 2,
                        credits_validation: true,
                        required: true
                    },
                    unit1: {
                        maxlength: 4,
                        required: true
                    },
                    unit2: {
                        duration_validation: true,
                        maxlength: 4,
                        required: true
                    },
                    unit3: {
                        duration_validation1: true,
                        maxlength: 4,
                        required: true
                    },
                    unit4: {
                        maxlength: 4,
                        required: true
                    },
                    short1: {
                        maxlength: 20,
                        required: true
                    },
                },
                messages: {
                    spec: {
                        required: " This Field is Required",
                        maxlength: "Data too long"
                    },
                    type: {
                        required: "This Field is Required"
                    },
                    mode: {
                        required: "This Field is Required"
                    },
                    acro: {
                        required: "This Field is Required"
                    },
                    department: {
                        required: "This Field is Required"
                    },
                    credits: {
                        required: " This Field is Required",
                        maxlength: "Please Enter Not More Then 3 Digits"

                    },
                    title: {
                        required: "This Field is Required"
                    },
                    short: {
                        required: " This Field is Required"
                    },
                    terms: {
                        required: " This Field is Required",
                        maxlength: "Please Enter Not More Then 2 Digits"
                    },
                    max: {
                        required: " This Field is Required",
                        maxlength: "Please Enter Not More Then 2 Digits"
                    },
                    min: {
                        required: " This Field is Required",
                        maxlength: "Please Enter Not More Then 2 Digits"
                    },
                    Termmin: {
                        required: " This Field is Required",
                        maxlength: "Please Enter Not More Then 2 Digits"
                    },
                    Termmax: {
                        required: " This Field is Required",
                        maxlength: "Please Enter Not More Then 2 Digits"

                    },
                    terms1: {
                        required: " This Field is Required",
                        maxlength: "Please Enter Not More Then 2 Digits"

                    },
                    terms2: {
                        required: " This Field is Required",
                        maxlength: "Please Enter Not More Then 2 Digits"

                    },
                    unit1: {
                        required: " This Field is Required"
                    },
                    unit2: {
                        required: " This Field is Required"
                    },
                    unit3: {
                        required: " This Field is Required"
                    },
                    unit4: {
                        required: " This Field is Required"
                    },
                    short1: {
                        required: " This Field is Required"
                    },
                },
                errorClass: "help-inline",
                errorElement: "span",
                highlight: function(element, errorClass, validClass) {
                    $(element).parent().parent().addClass('error');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).parent().parent().removeClass('error');
                    $(element).parent().parent().addClass('success');
                }
            });


            $.validator.addMethod("noSpecialChars", function(value, element) {
                return this.optional(element) || /^[a-zA-Z\s\.\&\_]+$/i.test(value);
            }, "This must contain only space letters and underscore.");



            $.validator.addMethod("duration_validation", function(value, element) {
                var max1 = document.getElementById('max').value;
                var min1 = document.getElementById('min').value;
                var unit1 = document.getElementById('unit').value;
                var unit2 = document.getElementById('unit2').value;
                var max_duration = max1 * unit1;
                var min_duration = min1 * unit2;
                if (min_duration > max_duration)
                {

                    return false;
                }
                return true;
            }, "Minimum Duration should be less than Maximum Duration");

            $.validator.addMethod("duration_validation1", function(value, element) {
                var Termmax1 = document.getElementById('Termmax').value;
                var Termmin1 = document.getElementById('Termmin').value;
                var unit4 = document.getElementById('unit4').value;
                var unit5 = document.getElementById('unit3').value;
                var max_duration = Termmax1 * unit4;
                var min_duration = Termmin1 * unit5;
                if (min_duration > max_duration)
                {

                    return false;
                }
                return true;
            }, "Term Minimum Duration should be less than Term Maximum Duration");

            $.validator.addMethod("credits_validation", function(value, element) {
                var terms2 = document.getElementById('terms2').value;
                var terms1 = document.getElementById('terms1').value;

                if (terms1 > terms2)
                {

                    return false;
                }
                return true;
            }, "Minimum Credits should be less than Maximum Credits");



            $("#program_form").validate();

            $.validator.addMethod("onlyDigit", function(value, element) {
                return this.optional(element) || /^[0-9\_]+$/i.test(value);
            }, "This must contain only Numbers.");

        });


</script>




<script type="text/javascript">

    function fun_title1()
    {

        var specialization = document.getElementById('spec').value;

        var title = " in " + specialization;
        document.getElementById('title1').value = title;
        var spec_acronym = document.getElementById('acro').value;
        var short_name = " in " + spec_acronym;
        document.getElementById('short1').value = short_name;
    }
    function fun_title()
    {

        var specialization = document.getElementById('spec').value;

        var title = " in " + specialization;
        document.getElementById('title1').value = title;

    }

    function short_name123()
    {

        var spec_acronym = document.getElementById('acro').value;
        var short_name = " in " + spec_acronym;
        document.getElementById('short1').value = short_name;
    }
</script>