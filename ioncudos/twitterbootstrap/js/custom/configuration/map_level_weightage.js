$(document).ready(function () {

    /*To Update Weightage of Faculty	*/
    $('#update_map_level').on('click', function () {

        var checked = []
        $("input[name='status[]']:checked").each(function () {
            $(this).val('1')
        });


        var map_level_name = $("input[name='map_level_name[]']")
                .map(function () {
                    return $(this).val();
                }).get();
        var priority = $("input[name='priority[]']")
                .map(function () {
                    return $(this).val();
                }).get();
        var status = $("input[name='status[]']")
                .map(function () {
                    return $(this).val();
                }).get();
        /*var weightage=$("input[name='weightage[]'")
         .map(function(){return $(this).val();}).get();*/

        var chkidArr = [];
        var totalArr = [];
        var totalArr1 = [];
        $('.chk').each(function () {
            chkidArr.push($(this).val());

            var current = $(this).val();
            $(this).closest('td').next('td').children('input').removeAttr('disabled');
            totalArr1.push($(this).parents("tr").find(".weight").val());
            if ($(this).is(':checked')) {
                var current = $(this).val();
                $(this).closest('td').next('td').children('input').removeAttr('disabled');
                totalArr.push($(this).parents("tr").find(".weight").val())
            }
        });
        totalArr = totalArr.map(Number);
        var tota = totalArr.reduce(function (a, b) {
            return a + b
        });

        //if (tota == '100.00') {
        post_data = {'map_level_name': map_level_name, 'priority': priority, 'status': status, 'weightage': totalArr1, "total": tota}

        $.ajax({type: 'POST',
            url: base_url + 'configuration/map_level_weightage/update_map',
            data: post_data,
            dataType: 'json',
            success: function (data)
            {
                success_modal();

                //code is commented for future use
                /*if ((data.total == '100.00')) {
                 //
                 //} else {
                 //$("#etotal").html("Percentage of weightage distribution should be equal to 100%.");
                 //}*/
            }
        });
        //} else {
        //$("#etotal").html("Percentage of weightage distribution should be equal to 100%.");
        //}
    });
    /** Reloading Page **/
    $('#ok').on('click', function () {
        location.reload();
    });

    /** Modal Call**/
    function success_modal(msg) {
        $('#myModal_suc').modal('show');
    }


    /** Check Box Validation **/
    $("[type=checkbox]").click(function () {
        var chkidArr = [];
        var totalArr = [];

        $('.chk').each(function () {
            chkidArr.push($(this).val());
            if ($(this).is(':checked')) {
                var current = $(this).val();
                $(this).closest('td').next('td').children('input').removeAttr('disabled');
                totalArr.push($(this).parents("tr").find(".weight").val())
            }
            if ($(this).is(':unchecked')) {
                ($(this).parents("tr").find(".weight").val("00.00"))
                $(this).closest('td').next('td').children('input').attr('disabled', "disabled");
            }
        });

        totalArr = totalArr.map(Number);
        var total = totalArr.reduce(function (a, b) {
            return a + b
        });
        if (total != NaN) {
            $('#total').val(total);
        }

        //code is commented for future use
        /*if (total == '100.00') {
         $("#etotal").html(" ");
         } else {
         $("#etotal").html("Percentage of weightage distribution should be equal to 100%.");
         }*/
        $("#tog input").prop('readonly', false);

    });

    /**Weightage Validation and calculation **/
    $("[type=text]").blur(function () {
        var chkidArr = [];
        var totalArr = [];
        $('.chk').each(function () {
            if ($(this).is(':checked')) {
                var current = $(this).val();
                totalArr.push($(this).parents("tr").find(".weight").val())
            }
            chkidArr.push($(this).val());
        });
        totalArr = totalArr.map(Number);
        if (parseInt(totalArr) < 0 || isNaN(totalArr)) {
        }
        var total = totalArr.reduce(function (a, b) {
            return a + b
        });
        $('#total').val(total);

        //code is commented for future use
        /*if (total != 100) {
         $("#etotal").html("Percentage of weightage distribution should be equal to 100%. _TEST");
         } else {
         $("#etotal").html(" ");
         }*/
    });

    /** **/
    jQuery('.weight').keyup(function () {
        this.value = this.value.replace(/[^0-9\.]/g, '');
    });

});

