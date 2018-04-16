<?php 
if ($this->rbac->has_permission('RBAC_USER', 'create')):
    echo '<div class="pull-right" id="create_button_div">
        <a class="btn btn-primary btn-sm" href="'.APP_BASE.'rbac/rbac_users/create">
            '.$this->lang->line("user_create_user").'
        </a>
    </div>';
endif; ?>
<div class="table-responsive">
    <?php echo generate_gird($grid_config, "rbac_users_list")?>
</div>

<script type="text/javascript">
    $(function ($) {
        $(document).on('click', '.delete-record', function (e) {
            e.preventDefault();
            var data = {'user_id': $(this).data('user_id')}
            var row = $(this).closest('tr');
            BootstrapDialog.show({
                cssClass: 'modal-warning',
                title: 'Warning!',
                message: 'Do you want to delete the record?',
                buttons: [{
                        label: 'Cancel',
                        action: function (dialog) {
                            dialog.close();
                        }
                    }, {
                        label: 'Delete',
                        action: function (dialog) {
                            $.ajax({
                                url: '<?= APP_BASE ?>rbac/rbac_users/delete',
                                method: 'POST',
                                data: data,
                                success: function (result) {
                                    dialog.close();
                                    row.hide(result);
                                    var response=JSON.parse(result);
                                    myApp.modal.alert(response);
                                },
                                error: function (error) {
                                    dialog.close();
                                    var param={
                                        'type':'danger',
                                        'title':'Error',
                                        'message':error.statusText
                                    }
                                    myApp.modal.alert(param);
                                }
                            });
                        }
                    }]
            });

        });
        $('div.DTTT_container').prepend($('#create_button_div'));
    });
</script>