//application_setting.js

//Function is to update settings.
$("#save").click(function(){
        var post_data = $("#application_setting_form").serialize();
        
        $.ajax({
                type: "POST",
                async:false,
                url: base_url + 'configuration/application_setting/update_settings',
                data: post_data,
                datatype: "json",
                success: function (data) {
                        $("#loading").hide();
                        if(data==1){
                                location.reload();
                        }
                }
        });     
});

//Function is to reset form
$('#reset_form').click(function () {
        location.reload();
});
