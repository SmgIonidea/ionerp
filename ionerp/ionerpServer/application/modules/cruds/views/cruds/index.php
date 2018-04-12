
<section class="col-lg-12 connectedSortable">

    <div class="row">
        <div class="container">
            <form id="crud_form" name="crud_form">
                <div class="form-group row">
                    <label for="db_tables" class="col-sm-2 col-form-label">Select Table</label>
                    <div class="col-sm-3">                        
                        <select name="db_tables[]" multiple id="db_tables" class="db_tables form-control">
                            <?php
                            foreach ($table_list as $key => $val) {
                                echo "<option value=\"$key\">$val</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="db_tables" class="col-sm-2 col-form-label">
                        Create Module                        
                    </label>
                    <div class="col-sm-3">                        
                        <div class="col-sm-1" style="margin: 0px;padding: 0px;">
                            <input class="form-check-input" type="checkbox" name="flag_module" id="flag_module" value="1">
                        </div>
                        <div class="col-sm-11" style="padding: 0px;">
                            <input type="text" placeholder="Module name" name="module_name" id="module_name" value="" class="form-control" style="display: none;">
                        </div>
                    </div>
                </div>  
                <div class="form-group row">
                    <label for="db_tables" class="col-sm-2 col-form-label">
                        Use custom datatable                      
                    </label>
                    <div class="col-sm-3">                        
                        <div class="col-sm-1" style="margin: 0px;padding: 0px;">
                            <input class="form-check-input" type="checkbox" name="data_table" id="data_table" value="1">
                        </div>                        
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2">I want CRUD of</label>
                    <div class="col-sm-10">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" type="checkbox" name="crud_of[]" value="M"> Model
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" type="checkbox" name="crud_of[]" value="V"> View
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" type="checkbox" name="crud_of[]" value="C"> Controller
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="offset-sm-2 col-sm-10">
                        <button type="submit" class="btn btn-primary">Go</button>
                    </div>
                </div>
            </form>
        </div>     
    </div>
</section>
<script type="text/javascript">
    $(document).ready(function () {
        $("#crud_form").on('submit', function (e) {
            e.preventDefault();
            //alert('ok');
            $.ajax({
                url: "cruds/master_crud",
                method: 'post',
                data: $(this).serialize(),
                success: function (result) {
                    $("#div1").html(result);
                }
            });
        });
        $('#db_tables').multiselect({
            columns: 1,
            placeholder: 'Select Table',
            search: true,
            selectAll: true
        });
        $('#flag_module').on('click',function(){
            if($(this).is(":checked")){
                $('#module_name').show();
            }else{
                $('#module_name').hide();
            }
        });
    });
</script>