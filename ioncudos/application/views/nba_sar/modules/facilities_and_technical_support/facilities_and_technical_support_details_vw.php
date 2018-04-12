<?php
//  Note:there should be defferent Department for each program type id or program type
/**
 * Description          :   View page for Facilities and Technical Support details	
 * Created              :   28-06-2017
 * Author               :   Shayista Mulla
 * Modification History :
 *   Date                   Modified By                     Description
  ---------------------------------------------------------------------------------------------- */
?>

<?php if ($pgm_type_id == 42) { ?>
    <div class="accordion-group ">
        <a class="brand-custom cursor_pointer " data-toggle="collapse" href="#collapse1" style="text-decoration:none;">
            <h5><b>&nbsp;<i class="icon-chevron-down icon-black"  data-toggle="collapse" > </i>
                    <?php echo "Adequate and well equipped laboratories, and manpower details"; ?></b></h5>
        </a>
    </div>					
    <div class="accordion-group">
        <div id="collapse1" class="panel-collapse collapse in">
            <div class="row-fluid">										  
                <table class="table table-bordered table-hover" id="example_adequate" style="width:100%;">
                    <thead>
                        <tr>
                            <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >Sl.No</th>
                            <th>Name of the Laboratory</th><th>No. of students </th><th>Name of the equipment</th><th>Weekly utilization status</th><th>Name of the technical staff</th><th>Designation</th><th>Qualification</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td><td>No Data to Display</td><td></td><td></td><td></td><td></td><td></td><td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="row-fluid" id="">	<br/>									  
                <form id="facility_adequate" name="facility_adequate" enctype='multipart/form-data' method="POST" class="form-horizontal">
                    <input type="hidden" id="fa_id" name="fa_id"/>
                    <div class="span6">				
                        <div class="control-group">
                            <p class="control-label" for="lab_name_1"> Name of the Laboratory: <font color="red"><b>*</b></font></p>
                            <div class="controls">
                                <div class="input-append ">
                                    <input placeholder="Enter lab name" type="text" id="lab_name_1" name="lab_name_1" class=""/>
                                </div>
                            </div>
                        </div>																							
                        <div class="control-group">
                            <p class="control-label" for="no_of_stud">Batch size: <font color="red"><b>*</b></font></p>
                            <div class="controls">
                                <div class="input-append ">
                                    <input placeholder="Enter batch size"  type="text" id="no_of_stud" name="no_of_stud" class="text-right allownumericwithoutdecimal"/>
                                </div>
                            </div>
                        </div>			
                        <div class="control-group">
                            <p class="control-label" for="equipment_name">Name of the equipment : <font color="red"><b></b></font></p>
                            <div class="controls">
                                <div class="input-append ">
                                    <input placeholder="Enter name of equipment"  type="text" id="equipment_name" name="equipment_name" class=""/>
                                </div>
                            </div>
                        </div>
                        <div class="control-group">
                            <p class="control-label" for="utilization_status">Weekly utilization status : <font color="red"><b></b></font></p>
                            <div class="controls">
                                <div class="input-append ">
                                    <input  placeholder="Enter status"  type="text" id="utilization_status" name="utilization_status" class=""/>
                                </div>
                            </div>
                        </div>	
                    </div>
                    <div class="span6">
                        <div class="control-group">
                            <p class="control-label" for="tech_staff_name">Name of the technical staff : <font color="red"><b></b></font></p>
                            <div class="controls">
                                <div class="input-append ">
                                    <input  placeholder="Enter name of technical staff"  type="text" id="tech_staff_name" name="tech_staff_name" class=""/>
                                </div>
                            </div>
                        </div>
                        <div class="control-group">
                            <p class="control-label" for="designation">Designation : <font color="red"><b></b></font></p>
                            <div class="controls">
                                <div class="input-append ">
                                    <input placeholder="Enter designation" type="text" id="designation" name="designation" class=""/>
                                </div>
                            </div>
                        </div>			
                        <div class="control-group">
                            <p class="control-label" for="qualification">Qualification : <font color="red"><b></b></font></p>
                            <div class="controls">
                                <div class="input-append ">
                                    <input   placeholder="Enter qualification" type="text" id="qualification" name="qualification" class=""/>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>	
                <div class="form-inline pull-right ">
                    <br/><br/><button value="1" type="button" name="update_facility_adequate" id="update_facility_adequate" class="btn btn-primary" style="display:none;"><i class="icon-file icon-white"></i><span></span> Update</button>
                    <button type="button" name="save_facility_adequate" id="save_facility_adequate" class="btn btn-primary"><i class="icon-file icon-white"></i><span></span> Save</button>
                    <button type="reset" id="reset_facility_adequate"  class="btn btn-info"><i class="icon-refresh icon-white"></i>Reset</button>
                    <br/><br/>
                </div>							
            </div>
        </div>
    </div>																		
    <br/>																			
    <div class="accordion-group ">
        <a class="brand-custom cursor_pointer " data-toggle="collapse" href="#collapse2" style="text-decoration:none;">
            <h5><b>&nbsp;<i class="icon-chevron-down icon-black"  data-toggle="collapse" > </i>
                    <?php echo "Safety measures in laboratories"; ?></b></h5>
        </a>
    </div>					
    <div class="accordion-group">
        <div id="collapse2" class="collapse">
            <div class="row-fluid">										  
                <table class="table table-bordered table-hover" id="example_laboratories" style="width:100%;">
                    <thead>
                        <tr>
                            <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="" aria-sort="ascending" >Sl.No</th>
                            <th>Name of the Laboratory</th><th>Safety measures</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td><td>No Data to Display</td><td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="row-fluid" id="">	<br/>									  
                <form id="laboratories_maintenance" name="laboratories_maintenance" enctype='multipart/form-data' method="POST" class="form-horizontal">
                    <input type="hidden" id="safety_lab_id" name="safety_lab_id"/>
                    <div class="span12">				
                        <div class="control-group">
                            <p class="control-label" for="lab_name"> Name of the Laboratory: <font color="red"><b>*</b></font></p>
                            <div class="controls">
                                <div class="input-append ">
                                    <input  placeholder="Enter lab name" type="text" id="lab_name" name="lab_name" class="required"/>
                                </div>
                            </div>
                        </div>																							
                        <div class="control-group">
                            <p class="control-label" for="safety_measures">Safety measures: <font color="red"><b></b></font></p>
                            <div class="controls">
                                <div>
                                    <textarea  placeholder="Enter Safety measures" style="width:40%" rows="7"  type="text" id="safety_measures" name="safety_measures" class=""></textarea>
                                </div>
                            </div>
                        </div>	
                    </div>
                </form>	
                <div class="form-inline pull-right ">
                    <br/><br/><button value="1" type="button" name="update_safety_measures_in_laboratories" id="update_safety_measures_in_laboratories" class="btn btn-primary" style="display:none;"><i class="icon-file icon-white"></i><span></span> Update</button>
                    <button type="button" name="save_safety_measures_in_laboratories" id="save_safety_measures_in_laboratories" class="btn btn-primary"><i class="icon-file icon-white"></i><span></span> Save</button>
                    <button type="reset" id="reset_safety_measures_in_laboratories"  class="clear_all btn btn-info"><i class="icon-refresh icon-white"></i>Reset</button>
                    <br/><br/>
                </div>							
            </div>
        </div>
    </div>
<?php } elseif ($pgm_type_id == 44) {
    ?>
    <div class="accordion-group ">
        <a class="brand-custom cursor_pointer " data-toggle="collapse" href="#collapse1" style="text-decoration:none;">
            <h5><b>&nbsp;<i class="icon-chevron-down icon-black"  data-toggle="collapse" > </i>
                    <?php echo "Adequate and well equipped laboratories, and manpower details"; ?></b></h5>
        </a>
    </div>					
    <div class="accordion-group">
        <div id="collapse1" class="panel-collapse collapse in">
            <div class="row-fluid">										  
                <table class="table table-bordered table-hover" id="example_adequate" style="width:100%;">
                    <thead>
                        <tr>
                            <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >Sl.No</th>
                            <th>Name of the Laboratory</th><th>No. of students </th><th>Name of the equipment</th><th>Weekly utilization status</th><th>Name of the technical staff</th><th>Designation</th><th>Qualification</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td><td>No Data to Display</td><td></td><td></td><td></td><td></td><td></td><td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="row-fluid" id="">	<br/>									  
                <form id="facility_adequate" name="facility_adequate" enctype='multipart/form-data' method="POST" class="form-horizontal">
                    <input type="hidden" id="fa_id" name="fa_id"/>
                    <div class="span6">				
                        <div class="control-group">
                            <p class="control-label" for="lab_name_1"> Name of the Laboratory: <font color="red"><b>*</b></font></p>
                            <div class="controls">
                                <div class="input-append ">
                                    <input placeholder="Enter lab name" type="text" id="lab_name_1" name="lab_name_1" class=""/>
                                </div>
                            </div>
                        </div>																							
                        <div class="control-group">
                            <p class="control-label" for="no_of_stud">Batch size: <font color="red"><b>*</b></font></p>
                            <div class="controls">
                                <div class="input-append ">
                                    <input placeholder="Enter batch size"  type="text" id="no_of_stud" name="no_of_stud" class="text-right allownumericwithoutdecimal"/>
                                </div>
                            </div>
                        </div>			
                        <div class="control-group">
                            <p class="control-label" for="equipment_name">Name of the equipment : <font color="red"><b></b></font></p>
                            <div class="controls">
                                <div class="input-append ">
                                    <input placeholder="Enter name of equipment"  type="text" id="equipment_name" name="equipment_name" class=""/>
                                </div>
                            </div>
                        </div>
                        <div class="control-group">
                            <p class="control-label" for="utilization_status">Weekly utilization status : <font color="red"><b></b></font></p>
                            <div class="controls">
                                <div class="input-append ">
                                    <input  placeholder="Enter status"  type="text" id="utilization_status" name="utilization_status" class=""/>
                                </div>
                            </div>
                        </div>	
                    </div>
                    <div class="span6">
                        <div class="control-group">
                            <p class="control-label" for="tech_staff_name">Name of the technical staff : <font color="red"><b></b></font></p>
                            <div class="controls">
                                <div class="input-append ">
                                    <input  placeholder="Enter name of technical staff"  type="text" id="tech_staff_name" name="tech_staff_name" class=""/>
                                </div>
                            </div>
                        </div>
                        <div class="control-group">
                            <p class="control-label" for="designation">Designation : <font color="red"><b></b></font></p>
                            <div class="controls">
                                <div class="input-append ">
                                    <input placeholder="Enter designation" type="text" id="designation" name="designation" class=""/>
                                </div>
                            </div>
                        </div>			
                        <div class="control-group">
                            <p class="control-label" for="qualification">Qualification : <font color="red"><b></b></font></p>
                            <div class="controls">
                                <div class="input-append ">
                                    <input   placeholder="Enter qualification" type="text" id="qualification" name="qualification" class=""/>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>	
                <div class="form-inline pull-right ">
                    <br/><br/><button value="1" type="button" name="update_facility_adequate" id="update_facility_adequate" class="btn btn-primary" style="display:none;"><i class="icon-file icon-white"></i><span></span> Update</button>
                    <button type="button" name="save_facility_adequate" id="save_facility_adequate" class="btn btn-primary"><i class="icon-file icon-white"></i><span></span> Save</button>
                    <button type="reset" id="reset_facility_adequate"  class="clear_all btn btn-info" onclick="reset_facility_adequate()" ><i class="icon-refresh icon-white"></i>Reset</button>
                    <br/><br/>
                </div>							
            </div>
        </div>
    </div>																		
    <br/>
<?php } elseif ($pgm_type_id == 45) {
    ?>
    <div class="accordion-group ">
        <a class="brand-custom cursor_pointer " data-toggle="collapse" href="#collapse1" style="text-decoration:none;">
            <h5><b>&nbsp;<i class="icon-chevron-down icon-black"  data-toggle="collapse" > </i>
                    <?php echo "Laboratory Details"; ?></b></h5>
        </a>
    </div>					
    <div class="accordion-group">
        <div id="collapse1" class="panel-collapse collapse in">
            <div class="row-fluid">										  
                <table class="table table-bordered table-hover" id="example_laboratory" style="width:100%;">
                    <thead>
                        <tr>
                            <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >Sl.No</th>
                            <th>Lab Description</th><th>Batch size </th><th>Availability of Manuals</th><th>Quality of instruments</th><th>Safety measures</th><th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="row-fluid">	<br/>									  
                <form id="laboratory" name="laboratory" enctype='multipart/form-data' method="POST" class="form-horizontal">
                    <input type="hidden" id="lab_id" name="lab_id"/>
                    <div class="span6">				
                        <div class="control-group">
                            <p class="control-label"> Lab Description : <font color="red"><b>*</b></font></p>
                            <div class="controls">
                                <div class="input-append ">
                                    <input placeholder="Enter Lab Description" type="text" id="lab_description" name="lab_description" class=""/>
                                </div>
                            </div>
                        </div>																							
                        <div class="control-group">
                            <p class="control-label">Batch Size: <font color="red"><b>*</b></font></p>
                            <div class="controls">
                                <div class="input-append ">
                                    <input placeholder="Enter Batch Size"  type="text" id="batch_size" name="batch_size" class="text-right allownumericwithoutdecimal"/>
                                </div>
                            </div>
                        </div>			
                        <div class="control-group">
                            <p class="control-label">Availability of Manuals : <font color="red"><b>*</b></font></p>
                            <div class="controls">
                                <div class="input-append ">
                                    <select id="manual_availabitity" name="manual_availabitity" autofocus = "autofocus" class="input-large" >
                                        <option value="Available">Available</option>
                                        <option value="Not Available">Not Available</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="control-group">
                            <p class="control-label">Quality of Instruments : <font color="red"><b></b></font></p>
                            <div class="controls">
                                <div class="input-append ">
                                    <input  placeholder="Enter Quality of Instruments"  type="text" id="instrument_quality" name="instrument_quality" class=""/>
                                </div>
                            </div>
                        </div>	
                    </div>
                    <div class="span6">
                        <div class="control-group">
                            <p class="control-label"> Safety Measures : <font color="red"><b>*</b></font></p>
                            <div class="controls">
                                <div class="input-append ">
                                    <textarea  placeholder="Enter Safety Measures" style="width:80%" rows="2" cols="50" type="text" id="safety_measures" name="safety_measures" class=""></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="control-group">
                            <p class="control-label">Remarks : <font color="red"><b></b></font></p>
                            <div class="controls">
                                <div class="input-append ">
                                    <textarea  placeholder="Enter Remarks" style="width:80%" rows="2" cols="50" type="text" id="remarks" name="remarks" class=""></textarea>
                                </div>
                            </div>
                        </div>			
                    </div>
                    <div class="form-inline pull-right ">
                        <br/><br/><button value="1" type="button" name="update_lab" id="update_lab" class="btn btn-primary" style="display:none;"><i class="icon-file icon-white"></i><span></span> Update</button>
                        <button type="button" name="save_lab" id="save_lab" class="btn btn-primary"><i class="icon-file icon-white"></i><span></span> Save</button>
                        <button type="reset" id="reset_lab"  class="btn btn-info"><i class="icon-refresh icon-white"></i>Reset</button>
                        <br/><br/>
                    </div>	
                </form>	
            </div>
        </div>
    </div>
    <div class="accordion-group ">
        <a class="brand-custom cursor_pointer " data-toggle="collapse" href="#collapse2" style="text-decoration:none;">
            <h5><b>&nbsp;<i class="icon-chevron-down icon-black"  data-toggle="collapse" > </i>
                    <?php echo "Equipments Details"; ?></b></h5>
        </a>
    </div>
    <div class="accordion-group">
        <div id="collapse2" class="panel-collapse collapse">
            <div class="row-fluid">										  
                <table class="table table-bordered table-hover" id="example_equipment" style="width:100%;">
                    <thead>
                        <tr>
                            <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >Sl.No</th>
                            <th>Equipment At</th><th>Name of the Equipment</th><th>Make & Model </th><th>SOP</th><th>Log Book</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td><td></td><td></td><td></td><td></td><td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="row-fluid">	<br/>									  
                <form id="equipment" name="equipment" enctype='multipart/form-data' method="POST" class="form-horizontal">
                    <input type="hidden" id="eqpt_id" name="eqpt_id"/>
                    <div class="span6">	
                        <div class="control-group">
                            <p class="control-label">Equipment At : <font color="red"><b>*</b></font></p>
                            <div class="controls">
                                <div class="input-append ">
                                    <select id="equipment_at" name="equipment_at" autofocus = "autofocus" class="input-large" >
                                        <option value="Instrument Room">Instrument Room</option>
                                        <option value="Machine Room">Machine Room</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="control-group">
                            <p class="control-label"> Name of the Equipment : <font color="red"><b>*</b></font></p>
                            <div class="controls">
                                <div class="input-append ">
                                    <input placeholder="Enter Name of the Equipment" type="text" id="equipment_name" name="equipment_name" class=""/>
                                </div>
                            </div>
                        </div>																							
                        <div class="control-group">
                            <p class="control-label">Make & Model : <font color="red"><b></b></font></p>
                            <div class="controls">
                                <div class="input-append ">
                                    <input placeholder="Enter Make & Model"  type="text" id="make_model" name="make_model" class=""/>
                                </div>
                            </div>
                        </div>		
                        <div class="control-group">
                            <p class="control-label">SOP Name & Code : <font color="red"><b></b></font></p>
                            <div class="controls">
                                <div class="input-append ">
                                    <input placeholder="Enter SOP Name & Code"  type="text" id="sop_name_code" name="sop_name_code" class=""/>
                                </div>
                            </div>
                        </div>	
                        <div class="control-group">
                            <p class="control-label">SOP : <font color="red"><b>*</b></font></p>
                            <div class="controls">
                                <div class="input-append ">
                                    <select id="sop" name="sop" autofocus = "autofocus" class="input-large" >
                                        <option value="Available">Available</option>
                                        <option value="Not Available">Not Available</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="span6">
                        <div class="control-group">
                            <p class="control-label">Log Book : <font color="red"><b>*</b></font></p>
                            <div class="controls">
                                <div class="input-append ">
                                    <select id="log_book" name="log_book" autofocus = "autofocus" class="input-large" >
                                        <option value="Available">Available</option>
                                        <option value="Not Available">Not Available</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="control-group">
                            <p class="control-label">Year of Purchase : <font color="red"><b></b></font></p>
                            <div class="controls">
                                <div class="input-prepend">
                                    <span class="add-on" id="btn" style="cursor:pointer; margin-top:1px;"><i class="icon-calendar"></i></span>
                                </div>
                                <input style="width:45%" class="input-medium datepicker" id="purchase_date" name="purchase_date" readonly="" type="text">
                            </div>
                        </div>
                        <div class="control-group">
                            <p class="control-label">Price : <font color="red"><b></b></font></p>
                            <div class="controls">
                                <div class="input-append ">
                                    <input  placeholder="Enter Price"  type="text" id="price" name="price" class="allownumericwithoutdecimal"/>
                                </div>
                            </div>
                        </div>
                        <div class="control-group">
                            <p class="control-label">Remarks : <font color="red"><b></b></font></p>
                            <div class="controls">
                                <div class="input-append ">
                                    <textarea  placeholder="Enter Remarks" style="width:80%" rows="2" cols="50" type="text" id="remarks" name="remarks" class=""></textarea>
                                </div>
                            </div>
                        </div>			
                    </div>
                    <div class="form-inline pull-right ">
                        <br/><br/><button value="1" type="button" name="update_eqpt" id="update_eqpt" class="btn btn-primary" style="display:none;"><i class="icon-file icon-white"></i><span></span> Update</button>
                        <button type="button" name="save_eqpt" id="save_eqpt" class="btn btn-primary"><i class="icon-file icon-white"></i><span></span> Save</button>
                        <button type="reset" id="reset_eqpt"  class="btn btn-info"><i class="icon-refresh icon-white"></i>Reset</button>
                        <br/><br/>
                    </div>	
                </form>	
            </div>
        </div>	
    </div>
    <div class="accordion-group ">
        <a class="brand-custom cursor_pointer " data-toggle="collapse" href="#collapse3" style="text-decoration:none;">
            <h5><b>&nbsp;<i class="icon-chevron-down icon-black"  data-toggle="collapse" > </i>
                    <?php echo "Non Teaching Support Details"; ?></b></h5>
        </a>
    </div>
    <div class="accordion-group">
        <div id="collapse3" class="panel-collapse collapse">
            <div class="row-fluid">										  
                <table class="table table-bordered table-hover" id="example_nts" style="width:100%;">
                    <thead>
                        <tr>
                            <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >Sl.No</th>
                            <th>Name of Technical Staff</th><th>Designation</th><th>Date of Joining </th><th>Qualification at Joining</th><th>Qualification Now</th><th>Other Technical Skills Gained</th><th>Responsibility</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="row-fluid">	<br/>									  
                <form id="non_teaching_support" name="non_teaching_support" enctype='multipart/form-data' method="POST" class="form-horizontal">
                    <input type="hidden" id="nts_id" name="nts_id"/>
                    <div class="span6">	
                        <div class="control-group">
                            <p class="control-label">Name of Technical Staff  : <font color="red"><b>*</b></font></p>
                            <div class="controls">
                                <div class="input-append ">
                                    <input placeholder="Enter Name of Technical Staff" type="text" id="staff_name" name="staff_name" class=""/>
                                </div>
                            </div>
                        </div>
                        <div class="control-group">
                            <p class="control-label"> Designation : <font color="red"><b>*</b></font></p>
                            <div class="controls">
                                <div class="input-append ">
                                    <input placeholder="Enter Designation" type="text" id="designation" name="designation" class=""/>
                                </div>
                            </div>
                        </div>		
                        <div class="control-group">
                            <p class="control-label">Date of Joining : <font color="red"><b>*</b></font></p>
                            <div class="controls">
                                <div class="input-prepend">
                                    <span class="add-on" id="btn_date" style="cursor:pointer; margin-top:1px;"><i class="icon-calendar"></i></span>
                                </div>
                                <input style="width:45%" class="input-medium" id="joining_date" name="joining_date" readonly="" type="text">
                            </div>
                        </div>
                        <div class="control-group">
                            <p class="control-label">Qualification at Joining : <font color="red"><b>*</b></font></p>
                            <div class="controls">
                                <div class="input-append ">
                                    <input placeholder="Enter Qualification at Joining"  type="text" id="quali_at_joining" name="quali_at_joining" class=""/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="span6">
                        <div class="control-group">
                            <p class="control-label">Qualification Now : <font color="red"><b></b></font></p>
                            <div class="controls">
                                <div class="input-append ">
                                    <input placeholder="Enter Qualification Now"  type="text" id="quali_now" name="quali_now" class=""/>
                                </div>
                            </div>
                        </div>	
                        <div class="control-group">
                            <p class="control-label">Other Technical Skills Gained&nbsp;: <font color="red"><b>*</b></font></p>
                            <div class="controls">
                                <div class="input-append ">
                                    <input placeholder="Enter Other Technical Skills Gained"  type="text" id="other_tech_skill_gained" name="other_tech_skill_gained" class=""/>
                                </div>
                            </div>
                        </div>
                        <div class="control-group">
                            <p class="control-label">Responsibility : <font color="red"><b></b></font></p>
                            <div class="controls">
                                <div class="input-append ">
                                    <textarea  placeholder="Enter Responsibility" style="width:80%" rows="2" cols="50" type="text" id="responsibility" name="responsibility" class=""></textarea>
                                </div>
                            </div>
                        </div>			
                    </div>
                    <div class="form-inline pull-right ">
                        <br/><br/><button value="1" type="button" name="update_nts" id="update_nts" class="btn btn-primary" style="display:none;"><i class="icon-file icon-white"></i><span></span> Update</button>
                        <button type="button" name="save_nts" id="save_nts" class="btn btn-primary"><i class="icon-file icon-white"></i><span></span> Save</button>
                        <button type="reset" id="reset_nts"  class="btn btn-info"><i class="icon-refresh icon-white"></i>Reset</button>
                        <br/><br/>
                    </div>	
                </form>	
            </div>
        </div>	
    </div>
    <br/>
<?php } ?>
<!-- End of file facilities_and_technical_support_details_vw.php 
        Location: .nba_sar/modules/facilities_and_technical_support/facilities_and_technical_support_details_vw.php  -->