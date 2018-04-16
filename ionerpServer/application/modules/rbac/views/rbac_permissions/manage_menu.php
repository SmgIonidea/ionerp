<form class="form-horizontal" action="#" name="manage_menu_form" id="manage_menu_form" method="post">
    <div class="row-fluid">
        <div class="col-sm-11" style="padding-left: 0px;">
            <label for="menu_module_id">Modules</label>        
            <?php echo form_dropdown('menu_module_id', $module_list, '', 'id="menu_module_id"'); ?>
        </div>
    </div>
    <br><br>    
    <div class="row" id="manage_menu_table" style="margin-left: 4px;">

    </div>
    <br>
    <div class="row-fluid">
        <input class="btn btn-primary" type="submit" name="save_menu_table" id="save_menu_table" value="Save">
    </div>
</form>