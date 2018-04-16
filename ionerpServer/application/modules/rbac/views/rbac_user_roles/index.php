<div class="pull-right" id="create_button_div">
    <a class="btn btn-primary btn-sm" href="<?= APP_BASE ?>rbac/rbac_user_roles/create">Create</a></div>
<div class="table-responsive">
    <?php
    generate_gird($grid_config, "rbac_user_roles_list");
    ?>
</div><script type="text/javascript">
    $(function ($) {


        $(document).on('click', '.delete-record', function (e) {
            e.preventDefault();
            var data = {'user_role_id': $(this).data('user_role_id')}
            var row = $(this).closest('tr');
            BootstrapDialog.show({
                title: 'Alert',
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
                                url: '<?= APP_BASE ?>rbac/rbac_user_roles/delete',
                                method: 'POST',
                                data: data,
                                success: function (result) {
                                    if (result == 'success') {
                                        dialog.close();
                                        row.hide();
                                        BootstrapDialog.alert('Record successfully deleted!');
                                    } else {
                                        dialog.close();
                                        BootstrapDialog.alert('Data deletion error,please contact site admin!');
                                    }
                                },
                                error: function (error) {
                                    dialog.close();
                                    BootstrapDialog.alert('Error:' + error);
                                }
                            });
                        }
                    }]
            });

        });
        $('div.DTTT_container').prepend($('#create_button_div'));
    });
</script>