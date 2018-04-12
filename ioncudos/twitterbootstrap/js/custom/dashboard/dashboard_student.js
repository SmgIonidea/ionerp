$(document).ready(function() {
   
    function displayMyAction() {
        $.ajax({type: "POST",
            url: base_url + 'dashboard/dashboard_student/student_survey',
            success: function(data) {
                console.log(data);
                $('#data_div').html(data);
                $('#table_student_survey').dataTable({
                    "sPaginationType" : "bootstrap",
                    "aaSorting" : [[0, "asc"]]
                });
            }
        });
    }
    
    displayMyAction();
    
});