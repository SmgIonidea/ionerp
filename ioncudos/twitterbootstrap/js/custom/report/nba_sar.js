$(document).ready(function(){
$('#nba_part_a').attr('checked','checked');
if($('#nba_part_a').attr('checked','checked'))
{
$('#part_a').attr('style','display:show');
}
});
$('.nba_sar_part').on('click',function(){
if($(this).attr('id') == 'nba_part_a'){
$('#part_a').attr('style','display:show');
$('#part_b').attr('style','display:none');
}else{
$('#part_a').attr('style','display:none');
$('#part_b').attr('style','display:show');
}
});

$('#add_more').on('click',function(){
var input_fields = "<div class='input-append' id='insti_list_clone'><input type='text' name='' id='' placeholder='Name Of the Institution'/><input type='text' name='yr' id='yr' placeholder='Year of Establishment'/><input type='text' name='' id='' placeholder='Location'/><button class='btn btn-danger delete_data' name='remove' id='remove'><i class='icon-remove-sign icon-white'></i></button></div>";
$('#insert_before').before(input_fields);
});

$('#nature_trust').on('click','.delete_data',function(){
$(this).parent().remove();
});

$('#add_funds').on('click',function(){
var input_fields = "<div class='input-append span12' id='cfy_clone'><input type='text' name='external_resource' id='external_resource' placeholder='Name of the external source'/><input type='text' name='current_yr' id='current_yr' placeholder='Previous Financial year'/><button class='btn btn-danger delete_funds' name='delete_funds' id='delete_funds'><i class='icon-remove-sign icon-white'></i></button></div>";
$('#funds').before(input_fields);
});

$('#sources_of_funds').on('click','.delete_funds',function(){
$(this).parent().remove();
});

$('#int_add_funds').on('click',function(){
var input_fields = "<div class='input-append span12' id='internal_cfy_clone'><input type='text' name='internal_funds' id='internal_funds' placeholder='Name of the internal source'/><input type='text' name='int_current_yr' id='int_current_yr' placeholder='Previous Financial year'/><button class='btn btn-danger int_delete_funds' name='int_delete_funds' id='int_delete_funds'><i class='icon-remove-sign icon-white'></i></button></div>";
$('#int_funds').before(input_fields);
});

$('#internal_sources_of_funds').on('click','.int_delete_funds',function(){
$(this).parent().remove();
});
//Drop-down menu code starts from here.
$('ul.nav li.dropdown').hover(function() {
  $(this).find('.dropdown-menu').stop(true, true).delay(100).fadeIn();
}, function() {
  $(this).find('.dropdown-menu').stop(true, true).delay(100).fadeOut();
});

//Departmental Information Tab Code starts from here
$('#dept_prog_hist_add_btn').on('click',function(){
var dept_data = "<div class='controls'><div class='controls span3'><select name='dept_prog' id='dept_prog'><option>Select Program</option><option>Bachelor of Engineering in Computer Science</option><option>Master of Technology in Mechanical</option><option>Master of Technology in Computer Science</option></select></div><div class='controls span9'><textarea id='dept_prog_desc' name='dept_prog_desc' rows='3' cols='20' style='margin: 0px 0px 10px; width: 656px; height: 50px;' placeholder='Enter Details Here'></textarea>&nbsp;&nbsp;&nbsp;&nbsp;<a class='cursor_pointer dept_prog_remove_btn' id='dept_prog_hist_remove_btn'><i class='icon-remove'></i><a></div></div></div>";
$('#dept_prog_add').before(dept_data);
});

$('#dept_prog_data').on('click','.dept_prog_remove_btn',function(){
$(this).parent().parent().remove();
});

$('#stud_num_add').on('click',function(){
var dept_stud_num_data = "<div class='input-append' id='dept_stud_data'><input type='text' name='dept_curnt_yr' id='dept_curnt_yr' placeholder='Previous Academic Year' /><input type='text' name='ug_stud_num' id='ug_stud_num' placeholder='Total No. of UG Students' /><input type='text' name='pg_stud_num' id='pg_stud_num' placeholder='Total No. of PG Students' /><input type='text' name='total_stud' id='total_stud' placeholder='Total No. Students' /><a class='btn btn-danger stud_num_remove' name='stud_num_remove' id='stud_num_remove'><i class='icon-remove-sign icon-white'></i></a></div>";
$('#dept_stud_data_one').before(dept_stud_num_data);
});

$('#dept_stud_num').on('click','.stud_num_remove',function(){
$(this).parent().remove();
});

$('#budget_add').on('click',function(){
var budget_data = "<br><div class='' id='budget'><table class='table table-bordered'><tr><th>Items</th><th>Budgeted in Current Financial Year</th><th>Actual Expenses In Current Financial Year</th></tr><tr><td>Laboratory Equipment</td><td><input type='text' name='lab_equip_budget' id='lab_equip_budget' placeholder='' /></td><td><input type='text' name='lab_equip_expenses' id='lab_equip_expenses' placeholder='' /></td></tr><tr><td class='pull-center'>Software</td><td><input type='text' name='soft_budget' id='soft_budget' placeholder='' /></td><td><input type='text' name='soft_expense' id='soft_expense' placeholder='' /></td></tr><tr><td>Laboratory consumable</td><td><input type='text' name='lab_consumable_budget' id='lab_consumable_budget' placeholder='' /></td><td><input type='text' name='lab_consumable_expense' id='lab_consumable_expense' placeholder='' /></td></tr><tr><td>Maintenance and spares</td><td><input type='text' name='spares_budget' id='spares_budget' placeholder='' /></td><td><input type='text' name='spares_expense' id='spares_expense' placeholder='' /></td></tr><tr><td>Training and Travel</td><td><input type='text' name='training_budget' id='training_budget' placeholder='' /></td><td><input type='text' name='training_expense' id='training_expense' placeholder='' /></td></tr><tr><td>Miscellaneous expenses for academic activities</td><td><input type='text' name='miscel_budget' id='miscel_budget' placeholder='' /></td><td><input type='text' name='miscel_expense' id='miscel_expense' placeholder='' /></td></tr><tr><td>Total</td><td><input type='text' name='total_budget' id='total_budget' placeholder='' /></td><td><input type='text' name='total_expense' id='total_expense' placeholder='' /></td></tr></table><div class='pull-right' id='add_button'><a class='btn btn-danger budget_remove' name='budget_remove' id='budget_remove'><i class='icon-remove-sign icon-white'></i>Delete</a></div></div><br>";
$('#budget_div').before(budget_data);
});
$('#dept_budget').on('click','.budget_remove',function(){
$(this).parent().parent().remove();
});