var base_url = $('#get_base_url').val();
var st0;
var st1;
var st2;
var st3;
var st4;
var st5;
var st6;
//var clo;
var clo_po_map_opp;
var clo_po_mapped_count;
var st_1;
var st_2;
var st_3;
var st_4;
var st_5;
var st_6;
var st_7;
var st_8;
var total_map_strength;
var high_map_strength;
var medium_map_strength;
var low_map_strength;
var term_wise_actual_map_val;
var topic_st_1;
var topic_st_2;
var topic_st_3;
var topic_st_4;
var topic_st_5;
var topic_st_6;
var topic_st_7;
var topic_st_8;
var po_peo_map_opp;
var po_peo_map_count;

function my_action()
{
    var crclm_id = document.getElementById("crclm_name").value;

    var post_data = {
        'crclm_id': crclm_id
    }

    $.ajax({type: "POST",
        url: base_url + 'dashboard/dashboard/my_action',
        data: post_data,
        success: function(msg) {
            document.getElementById('my_action_data').innerHTML = msg;
        }
    });
}


function display()
{
    var crclm_id = document.getElementById("dept_id1").value;
    $("#crclm_for_course option[value=" + crclm_id + "]").attr('selected', 'selected');
    $("#course_topic option[value=" + crclm_id + "]").attr('selected', 'selected');
    //$('#crclm_for_course').attr('selected',crclm_id);

    if (!crclm_id)
        $("a#export").attr("href", "#");
    else
        $("a#export").attr("onclick", "generate_pdf();");

    var post_data = {
        'crclm_id': crclm_id
    }

    $.ajax({type: "POST",
        url: base_url + 'dashboard/dashboard/display_graph',
        data: post_data,
        dataType: 'json',
        success: function(json_graph_data) {
            if (json_graph_data.graph_data.length > 0) {
                $.each(json_graph_data.graph_data, function() {
                    st0 = this['not_created'];
                    st1 = this['review_pending'];
                    st2 = this['review_rework'];
                    st3 = this['review_completed'];
                    st4 = this['approval_pending'];
                    st5 = this['approval_rework'];
                    st6 = this['approved'];
                    //clo = this['clo_po_map'];
                    clo_po_map_opp = this['clo_po_map_opp'];
                    clo_po_mapped_count = this['clo_po_mapped_count'];
                    //po = this['po_clo_map'];
                    po_peo_map_opp = this['po_peo_map_opp'];
                    po_peo_map_count = this['po_peo_mapped_count'];
                });

                $('#chart1').empty();
                $('#chart1b').empty();
                $('#chart1c').empty();
                graph();
            }
        }
    });

    $.ajax({type: "POST",
        url: base_url + 'dashboard/dashboard/fetch_entity_state',
        data: post_data,
        success: function(msg) {
            document.getElementById('state_grid').innerHTML = msg;
        }
    });


    /* Ajax call to fetch the terms for clo to po mapping states*/
    var course_terms = {
        'curriculum_id': crclm_id
    }
    $.ajax({type: "POST",
        url: base_url + 'dashboard/dashboard/course_level_term',
        data: course_terms,
        success: function(terms) {
            document.getElementById('crclm_term_for_course').innerHTML = terms;
        }
    });

    /* Ajax Call Ends Here */

    /* Ajax call to fetch terms for tlo to clo mapping status*/
    var course_topics = {
        'curriculum_id': crclm_id
    }
    $.ajax({type: "POST",
        url: base_url + 'dashboard/dashboard/course_level_term',
        data: course_topics,
        success: function(topics) {
            document.getElementById('course_term_for_topic').innerHTML = topics;
        }
    });
    /* Ajax call Ends Here*/

}


function graph()
{

    var data = [
        ['State1: Not Created'+' - '+st0+' / '+4, st0], ['State2: Created / Review Pending'+' - '+st1+' / '+4, st1], ['State3: Review Rework'+' - '+st2+' / '+4, st2], ['State4: Reviewed'+' - '+st3+' / '+4, st3]
                , ['State5: Approval Pending'+' - '+st4+' / '+4, st4], ['State6: Approval Rework'+' - '+st5+' / '+4, st5], ['State7: Approved'+' - '+st6+' / '+4, st6]
    ];

    var plot2 = jQuery.jqplot('chart1', [data],
            {
                title: {
                    text: 'Program Level Entity States',
                    show: true, },
                seriesDefaults: {
                    renderer: jQuery.jqplot.PieRenderer,
                    rendererOptions: {
                        // Turn off filling of slices.
                        fill: true,
                        showDataLabels: true,
                        // Add a margin to separate the slices.

                        sliceMargin: 4,
                        // stroke the slices with a little thicker line.
                        lineWidth: 5
                    }
                },
                legend: {show: true, location: 'e'}
            });



    var s1 = [['Total Mapping Opportunities', po_peo_map_opp]];
    var s2 = [['Actual Mapped Opportunities', po_peo_map_count]];
	// var s1 =[[['PO to PEO  Mapping', po_peo_map_opp]], [['PO to PEO  Mapping', po_peo_map_count]]];
    // var s2 = [[['CO to PO Mapping', clo_po_map_opp]],[['CO to PO Mapping', clo_po_mapped_count]]];
    //var ticks = ['PO to PEO Mapping', 'CO to PO Mapping'];

    $.jqplot.config.enablePlugins = true;
    plot2 = $.jqplot('chart1b', [s1, s2], {
        title: {
            text: ' Program Level '+so+' to PEO Extent of Mapping',
            show: true,
        },
        seriesColors: ['blue', 'orange'],
        seriesDefaults: {
            renderer: $.jqplot.BarRenderer,
            rendererOptions: {
                barWidth: 50,
                barPadding: -50
            }
        },
        axesDefaults: {
            tickRenderer: $.jqplot.CanvasAxisTickRenderer,
            tickOptions: {
                fontFamily: 'Georgia',
                fontSize: '10pt',
                fontWeight: 'Bold',
                angle: -0
            }
        },
        axes: {
            xaxis: {
                renderer: $.jqplot.CategoryAxisRenderer,
            },
            yaxis: {
				// min:0,
                // max:100,
                tickOptions: {
                    formatString: '%d'
                },
            }
        },
		
    });

    $('#chart1b').bind('jqplotDataHighlight',
            function(ev, seriesIndex, pointIndex, data) {
                $('#info2').html('series: ' + seriesIndex + ', point: ' + pointIndex + ', data: ' + data);
            }
    );

    $('#chart1b').bind('jqplotDataUnhighlight',
            function(ev) {
                $('#info2').html('Nothing');
            }
    );
	
	    var clo_po_opp = [['Total Mapping Opportunities', clo_po_map_opp]];
		var clo_po_count = [['Actual Mapped Opportunities', clo_po_mapped_count]];
	// var s1 =[[['PO to PEO  Mapping', po_peo_map_opp]], [['PO to PEO  Mapping', po_peo_map_count]]];
    // var s2 = [[['CO to PO Mapping', clo_po_map_opp]],[['CO to PO Mapping', clo_po_mapped_count]]];
    //var ticks = ['PO to PEO Mapping', 'CO to PO Mapping'];

    $.jqplot.config.enablePlugins = true;
    plot2 = $.jqplot('chart1c', [clo_po_opp, clo_po_count], {
        title: {
            text: 'Program Level CO to '+so+' Extent of Mapping',
            show: true,
        },
        seriesColors: ['blue', 'orange'],
        seriesDefaults: {
            renderer: $.jqplot.BarRenderer,
            rendererOptions: {
                barWidth: 50,
                barPadding: -50
            }
        },
        axesDefaults: {
            tickRenderer: $.jqplot.CanvasAxisTickRenderer,
            tickOptions: {
                fontFamily: 'Georgia',
                fontSize: '10pt',
                fontWeight: 'Bold',
                angle: -0
            }
        },
        axes: {
            xaxis: {
                renderer: $.jqplot.CategoryAxisRenderer,
            },
            yaxis: {
				// min:0,
                // max:100,
                tickOptions: {
                    formatString: '%d'
                },
            }
        },
		
    });

    $('#chart1c').bind('jqplotDataHighlight',
            function(ev, seriesIndex, pointIndex, data) {
                $('#info2').html('series: ' + seriesIndex + ', point: ' + pointIndex + ', data: ' + data);
            }
    );

    $('#chart1c').bind('jqplotDataUnhighlight',
            function(ev) {
                $('#info2').html('Nothing');
            }
    );

}

function curriculums()
{
    var data_val = document.getElementById('dept_id').value;

    var post_data = {
        'dept_id': data_val
    }

    $.ajax({type: "POST",
        url: base_url + 'dashboard/dashboard/fetch_curriculum',
        data: post_data,
        success: function(msg) {
            document.getElementById('crclm_list').innerHTML = msg;
        }
    });

}

function active_curriculums()
{
    var data_val = document.getElementById('dept_id').value;
    var isChecked = $('#state:checked').val() ? true : false;
    if (isChecked == true)
    {
        var post_data = {
            'dept_id': data_val
        }

        $.ajax({type: "POST",
            url: base_url + 'dashboard/dashboard/active_curriculum',
            data: post_data,
            success: function(msg) {
                document.getElementById('crclm_list').innerHTML = msg;
            }
        });

    }

    else
    {
        var post_data = {
            'dept_id': data_val
        }

        $.ajax({type: "POST",
            url: base_url + 'dashboard/dashboard/fetch_curriculum',
            data: post_data,
            success: function(msg) {
                document.getElementById('crclm_list').innerHTML = msg;
            }
        });
    }
}

function dept_active_curriculums()
{
    var data_val = document.getElementById('dept_name').value;
    var isChecked = $('#state1:checked').val() ? true : false;
    if (isChecked == true)
    {
        var post_data = {
            'dept_id': data_val
        }
        $.ajax({type: "POST",
            url: base_url + 'dashboard/dashboard/active_curriculum',
            data: post_data,
            success: function(msg) {
                document.getElementById('crclm_list').innerHTML = msg;
            }
        });
    }

    else
    {
        var post_data = {
            'dept_id': data_val
        }
        $.ajax({type: "POST",
            url: base_url + 'dashboard/dashboard/fetch_curriculum',
            data: post_data,
            success: function(msg) {
                document.getElementById('crclm_list').innerHTML = msg;
            }
        });
    }

}

function dept_curriculum()
{
    var data_val = document.getElementById('dept_crclm').value;
    var post_data = {
        'dept_id': data_val
    }
    $.ajax({type: "POST",
        url: base_url + 'dashboard/dashboard/dept_active_curriculum',
        data: post_data,
        success: function(msg) {
            $('#dash_crclm').html(msg);
            document.getElementById('crclm_for_course').innerHTML = msg;
            document.getElementById('course_topic').innerHTML = msg;
        }
    });

}

$('#crclm_for_course').on('change', function() {

    var crclm_id = $('#crclm_for_course').val();
    var post_data = {
        'curriculum_id': crclm_id
    }
    $.ajax({type: "POST",
        url: base_url + 'dashboard/dashboard/course_level_term',
        data: post_data,
        success: function(msg) {
            document.getElementById('crclm_term_for_course').innerHTML = msg;
        }
    });

});

$('#crclm_term_for_course').on('change', function() {
    var term_id = $('#crclm_term_for_course').val();
    var crclm_id = $('#crclm_for_course').val();
    var post_data = {
        'curriculum_id': crclm_id,
        'term_id': term_id
    }
    $.ajax({type: "POST",
        url: base_url + 'dashboard/dashboard/term_course_data',
        data: post_data,
        dataType: 'json',
        success: function(msg) {
            st_1 = msg.not_created;
            st_2 = msg.review_pending;
            st_3 = msg.review_rework;
            st_4 = msg.review_completed;
            st_5 = msg.approval_pending;
            st_6 = msg.approval_rework;
            st_7 = msg.approved;
            st_8 = msg.size;
            total_map_strength = msg.term_total_map_strength;
            high_map_strength = msg.term_high_map_val;
            medium_map_strength = msg.term_mid_map_val;
            low_map_strength = msg.term_low_map_val;

            $('#course_clo_pie_chart').empty();
            $('#total_map_extent').empty();
            $('#total_map_strength').empty();
            // $('#chart1b').empty();
            if (msg.no_data == 0) {
                $('#course_clo_pie_chart').html("<b>No Courses are created for This Curriculum in this Term</b>");
            } else {
                if (total_map_strength == 0 && high_map_strength == 0 && medium_map_strength == 0 && low_map_strength) {
                    course_piechart();
                    $('#course_state_table').html(msg.course_state_table);
                }
                else {
                    course_piechart();
                    term_crs_extent_map(msg.term_crs_total_opp, msg.term_crs_mapped_opp);
                    term_crs_map_strength(msg.high_map_val, msg.moderate_map_val, msg.low_map_val);
                    $('#course_state_table').html(msg.course_state_table);
                }
            }
        }
    });
});

// Plotting Pie Chart

function course_piechart()
{
    var data = [
        ['State1: Not Created'+' - '+parseInt(st_1)+' / '+st_8, parseInt(st_1)], ['State2: Created / Review Pending'+' - '+parseInt(st_2)+' / '+st_8, parseInt(st_2)], ['State3: Review Rework'+' - '+parseInt(st_3)+' / '+st_8, parseInt(st_3)], ['State4: Reviewed'+' - '+parseInt(st_4)+' / '+st_8, parseInt(st_4)], ['State5: Approval Pending'+' - '+parseInt(st_5)+' / '+st_8, parseInt(st_5)], ['State6: Approval Rework'+' - '+parseInt(st_6)+' / '+st_8, parseInt(st_6)], ['State7: Approved'+' - '+parseInt(st_7)+' / '+st_8, parseInt(st_7)]
    ];

    var pie_plot = jQuery.jqplot('course_clo_pie_chart', [data],
            {
                title: {
                    text: 'Term-wise Course CO States',
                    show: true, },
                seriesDefaults: {
                    renderer: jQuery.jqplot.PieRenderer,
                    rendererOptions: {
                        // Turn off filling of slices.
                        fill: true,
                        showDataLabels: true,
                        // Add a margin to separate the slices.

                        sliceMargin: 4,
                        // stroke the slices with a little thicker line.
                        lineWidth: 5
                    }
                },
                legend: {show: true, location: 'e'}
            });

}

// bar chart
function term_crs_extent_map(term_crs_total_opp, term_crs_mapped_opp) {
    $.jqplot.config.enablePlugins = true;
    // var chartData = [[['Total Map', total_map_strength]],[['High Map', high_map_strength]], [['Medium Map', medium_map_strength]], [['Low Map', low_map_strength]]];
	term_wise_actual_map_val = term_crs_mapped_opp;
	var chartData = [[['Total Mapping Opportunities', term_crs_total_opp]], [['Actual Mapped Opportunities', term_crs_mapped_opp]]];
    //var s1 = [total_map_strength*10, high_map_strength*10, medium_map_strength*10, low_map_strength*10];
   // var ticks = ['High Map Strength', 'Medium Map Strength', 'Low Map Strength'];

    plot2 = $.jqplot('total_map_extent', chartData, {
        title: {text: 'Term-wise CO to '+so+' Extent of Mapping',
            show: true, },
        seriesColors: ['blue', 'orange', 'red'],
        seriesDefaults: {
            renderer: $.jqplot.BarRenderer,
            rendererOptions: {
                barWidth: 50,
                barPadding: -50
            }
        },
        axesDefaults: {
            tickRenderer: $.jqplot.CanvasAxisTickRenderer,
			tickOptions: {
                fontFamily: 'Georgia',
                fontSize: '10pt',
                fontWeight: 'Bold',
                angle: -0
            }
        },
        axes: {
            xaxis: {
                renderer: $.jqplot.CategoryAxisRenderer,
            },
            yaxis: {
				// min:0,
                // max:100,
                tickOptions: {
                    formatString: '%d'
                },
            }
        },
		/* canvasOverlay: {
              show: true,
              objects: [
				{horizontalLine: {
                    name: 'pebbles',
					y: total_map_strength,
                    lineWidth: 1,
                    xOffset: 0,
                    color: 'green',
                    shadow: false
                }}
				]
            }, */
        highlighter: {
            show: true,
            showLabel: true,
            tooltipAxes: 'y',
            sizeAdjust: 7.5, tooltipLocation: 'ne'
        },
        // legend: {
            // show: true,
            // placement: 'outside',
            // labels: ticks
        // },
    });
}


// bar chart
function term_crs_map_strength(high_map_val, moderate_map_val, low_map_val) {
    $.jqplot.config.enablePlugins = true;
    // var chartData = [[['Total Map', total_map_strength]],[['High Map', high_map_strength]], [['Medium Map', medium_map_strength]], [['Low Map', low_map_strength]]];
	
	var chartData = [[['High Map', high_map_strength]], [['Medium Map', medium_map_strength]], [['Low Map', low_map_strength]]];
    //var s1 = [total_map_strength*10, high_map_strength*10, medium_map_strength*10, low_map_strength*10];
    var ticks = ['High Map Strength: '+high_map_val+'/'+term_wise_actual_map_val, 'Moderate Map Strength: '+moderate_map_val+'/'+term_wise_actual_map_val, 'Low Map Strength: '+low_map_val+'/'+term_wise_actual_map_val];

    plot2 = $.jqplot('total_map_strength', chartData, {
        title: {text: 'Term-wise CO to '+so+' Mapping Strength',
            show: true, },
        seriesColors: ['blue', 'orange', 'red'],
        seriesDefaults: {
            renderer: $.jqplot.BarRenderer,
            rendererOptions: {
                barWidth: 50,
                barPadding: -50
            }
        },
        axesDefaults: {
            tickRenderer: $.jqplot.CanvasAxisTickRenderer,
			tickOptions: {
                fontFamily: 'Georgia',
                fontSize: '10pt',
                fontWeight: 'Bold',
                angle: -0
            }
        },
        axes: {
            xaxis: {
                renderer: $.jqplot.CategoryAxisRenderer,
            },
            yaxis: {
				// min:0,
                // max:100,
                tickOptions: {
                    formatString: '%d%'
                },
            }
        },
		/* canvasOverlay: {
              show: true,
              objects: [
				{horizontalLine: {
                    name: 'pebbles',
					y: total_map_strength,
                    lineWidth: 1,
                    xOffset: 0,
                    color: 'green',
                    shadow: false
                }}
				]
            }, */
        highlighter: {
            show: true,
            showLabel: true,
            tooltipAxes: 'y',
            sizeAdjust: 7.5, tooltipLocation: 'ne'
        },
        legend: {
            show: true,
            placement: 'inside',
            labels: ticks
        },
    });
}
////

$('.crs_mapping_popup').live('click', function() {
    var crs_id = $(this).attr('abbr');
	var crclm_id = $('#crclm_for_course').val();
    var post_data = {
        'crs_id': crs_id,
		'crclm_id' : crclm_id
    }
    $.ajax({
        type: "POST",
        url: base_url + 'dashboard/dashboard/crs_mapping_strength',
        data: post_data,
        dataType: 'json',
        success: function(msg) {
            $.jqplot.config.enablePlugins = true;
			
            // var series_data = [
                // [['Total Map', parseInt(msg.course_total_map_strength)]],
                // [['High Map', parseInt(msg.course_high_map_val)]],
                // [['Medium Map', parseInt(msg.course_mid_map_val)]],
                // [['Low Map', parseInt(msg.course_low_map_val)]]
            // ];
			$('#crs_title').html(msg.course_title+': CO to '+so+' Extent/Strength of Mapping');
			var series_data = [
                [['Total Mapping Opportunities', parseInt(msg.clo_po_map_opp_result)]],
                [['Actual Mapped Opportunities', parseInt(msg.clo_po_map_count)]]
            ];


            //var s1 = [total_map_strength*10, high_map_strength*10, medium_map_strength*10, low_map_strength*10];
            var ticks = ['Total Mapping Opportunities', 'Actual Mapped Opportunities', 'Medium Map', 'Low Map'];

            crs_map_plot = $.jqplot('crs_total_map_strength', series_data, {
                title: {
                    text: 'Course-wise CO to '+so+' Extent of Mapping',
                    show: true, },
                seriesColors: ['blue', 'orange', 'red'],
                seriesDefaults: {
                    renderer: $.jqplot.BarRenderer,
                    rendererOptions: {
                        barWidth: 50,
                        barPadding: -50
                    }
                },
                axesDefaults: {
                    tickRenderer: $.jqplot.CanvasAxisTickRenderer,
					tickOptions: {
                fontFamily: 'Georgia',
                fontSize: '10pt',
                fontWeight: 'Bold',
                angle: -0
            }
                },
				axes: {
					xaxis: {
						renderer: $.jqplot.CategoryAxisRenderer,
					},
					yaxis: {
						// min:0,
						// max:100,
						tickOptions: {
							formatString: '%d'
						},
					}
				},
				canvasOverlay: {
              show: true,
              objects: [
				{horizontalLine: {
                    name: 'pebbles',
					y: parseInt(msg.clo_po_map_opp_result),
                    lineWidth: 1,
                    xOffset: 0,
                    color: 'green',
                    shadow: false
                }}
				]
            },
        highlighter: {
            show: true,
            showLabel: true,
            tooltipAxes: 'y',
            sizeAdjust: 7.5, tooltipLocation: 'ne'
        },
                legend: {
                    show: true,
                    placement: 'outside',
                    labels: ticks
                },
            });

            // Pie chart
            var data = [['High Mapping:'+msg.high_map_val, parseInt(msg.course_high_map_val)], ['Medium Mapping:'+msg.mid_map_val, parseInt(msg.course_mid_map_val)], ['Low Mapping:'+msg.low_map_val, parseInt(msg.course_low_map_val)]];

            var crs_pie_plot = jQuery.jqplot('course_map_pie_chart', [data],
                    {
                        title: {
                            text: 'Course-wise CO to '+so+' Strength Mapping Distribution',
                            show: true, },
                        seriesColors: ['blue', 'orange', 'red'],
                        seriesDefaults: {
                            renderer: jQuery.jqplot.PieRenderer,
                            rendererOptions: {
                                // Turn off filling of slices.
                                fill: true,
                                showDataLabels: true,
                                // Add a margin to separate the slices.

                                sliceMargin: 4,
                                // stroke the slices with a little thicker line.
                                lineWidth: 5
                            }
                        },
                        legend: {show: true, location: 'e'}
                    });
            $("#crs_map_strength_modal").animate({"width": "800px", "height": "580px", "margin-left": "-400px", "margin-right": "-300px"}, 600, 'linear');
            $('#crs_map_strength_modal').modal('show');
            $('#crs_map_strength_modal').on('shown', function(e) {
                crs_map_plot.replot();
                crs_pie_plot.replot();
            });

        }

    });

});


function dept_curriculum_active()
{
    var data_val = document.getElementById('dept_crclm').value;
    var isChecked = $('#state2:checked').val() ? true : false;
    if (isChecked == true)
    {
        var post_data = {
            'dept_id': data_val
        }

        $.ajax({type: "POST",
            url: base_url + 'dashboard/dashboard/dept_active_curriculum_1',
            data: post_data,
            success: function(msg) {
                document.getElementById('dept_id1').innerHTML = msg;
            }
        });
    }

    else
    {
        var post_data = {
            'dept_id': data_val
        }

        $.ajax({type: "POST",
            url: base_url + 'dashboard/dashboard/dept_active_curriculum',
            data: post_data,
            success: function(msg) {

                document.getElementById('dept_id1').innerHTML = msg;
            }
        });
    }
}


function fetch_graph_data()
{
    var crclm_id = document.getElementById("dept_id1").value;

    var post_data = {
        'crclm_id': crclm_id

    }

    $.ajax({type: "POST",
        url: base_url + 'dashboard/dashboard/fetch_entity_state',
        data: post_data,
        success: function(msg) {
            document.getElementById('chart1').innerHTML = msg;
        }
    });
}


$('#course_topic').on('change', function() {

    var crclm_id = $('#course_topic').val();
    var post_data = {
        'curriculum_id': crclm_id
    }
    $.ajax({type: "POST",
        url: base_url + 'dashboard/dashboard/course_level_term',
        data: post_data,
        success: function(msg) {
            document.getElementById('course_term_for_topic').innerHTML = msg;
        }
    });

});

$('#course_term_for_topic').on('change', function() {

    var crclm_id = $('#course_topic').val();
    var term_id = $('#course_term_for_topic').val();
    var post_data = {
        'curriculum_id': crclm_id,
        'term_id': term_id
    }
    $.ajax({type: "POST",
        url: base_url + 'dashboard/dashboard/term_course_list',
        data: post_data,
        success: function(crs_list) {
            document.getElementById('course_list').innerHTML = crs_list;
        }
    });

});

$('#course_list').on('change', function() {

    var crclm_id = $('#course_topic').val();
    var term_id = $('#course_term_for_topic').val();
    var crs_id = $('#course_list').val();
    var post_data = {
        'curriculum_id': crclm_id,
        'term_id': term_id,
        'course_id': crs_id
    }
    $.ajax({type: "POST",
        url: base_url + 'dashboard/dashboard/course_topic_list',
        data: post_data,
        dataType: 'json',
        success: function(topic_list) {
            topic_st_1 = topic_list.not_created;
            topic_st_2 = topic_list.review_pending;
            topic_st_3 = topic_list.review_rework;
            topic_st_4 = topic_list.review_completed;
            topic_st_5 = topic_list.approval_pending;
            topic_st_6 = topic_list.approval_rework;
            topic_st_7 = topic_list.approved;
            topic_st_8 = topic_list.topic_size;
            var tlo_clo_map_opp_data = topic_list.tlo_clo_map_opp;
            var tlo_clo_map_count_data = topic_list.tlo_clo_map_count;

            $('#topic_tlo_pie_chart').empty();
            $('#tlo_clo_bar_chart').empty();
            $('#no_data_msg').empty();
            $('#topic_state_table').empty();
            // $('#chart1b').empty();

            if (topic_list.no_data == 0) {
                $('#no_data_msg').html('<b>SLOs are not created for this Course</b>');
            }
            else {
                if (tlo_clo_map_count_data == 0) {
                    topic_piechart();
                    $('#topic_state_table').html(topic_list.topic_state_table);
                }
                else {
                    topic_piechart();
                    tlo_clo_map_bar_graph(tlo_clo_map_opp_data, tlo_clo_map_count_data);
                    $('#topic_state_table').html(topic_list.topic_state_table);
                }
            }

        }
    });

});

// Plotting Pie Chart

function topic_piechart()
{


    var data = [
        ['State1: Not Created'+' - '+topic_st_1+ ' / '+topic_st_8, parseInt(topic_st_1)], ['State2: Created / Review Pending'+' - '+topic_st_2+ ' / '+topic_st_8, parseInt(topic_st_2)], ['State3: Review Rework'+' - '+topic_st_3+ ' / '+topic_st_8, parseInt(topic_st_3)], ['State4: Reviewed'+' - '+topic_st_4+ ' / '+topic_st_8, parseInt(topic_st_4)], ['State5: Approval Pending'+' - '+topic_st_5+ ' / '+topic_st_8, parseInt(topic_st_5)], ['State6: Approval Rework'+' - '+topic_st_6+ ' / '+topic_st_8, parseInt(topic_st_6)], ['State7: Approved'+' - '+topic_st_7+ ' / '+topic_st_8, parseInt(topic_st_7)]
    ];

    var pie_plot = jQuery.jqplot('topic_tlo_pie_chart', [data],
            {
                title: {
                    text: 'Course-wise'+ entity_topic +' SLO to CO Mapping States',
                    show: true, },
                seriesDefaults: {
                    renderer: jQuery.jqplot.PieRenderer,
                    rendererOptions: {
                        // Turn off filling of slices.
                        fill: true,
                        showDataLabels: true,
                        // Add a margin to separate the slices.

                        sliceMargin: 4,
                        // stroke the slices with a little thicker line.
                        lineWidth: 5
                    }
                },
                legend: {show: true, location: 'e'}
            });


}

function tlo_clo_map_bar_graph(tlo_clo_map_opp_val, tlo_clo_map_count_val) {

    $.jqplot.config.enablePlugins = true;
    var series_data = [
        [['Total Mapping Opportunities', tlo_clo_map_opp_val]],
        [['Actual Mapped Opportunities', tlo_clo_map_count_val]],
    ];

    var ticks = ['Total Mapping Opportunities','Actual Mapped Opportunities'];

    crs_map_plot = $.jqplot('tlo_clo_bar_chart', series_data, {
        title: {
            text: 'Course-wise SLO to CO Extent of Mapping',
            show: true, },
        seriesColors: ['blue', 'orange', 'red'],
        seriesDefaults: {
            renderer: $.jqplot.BarRenderer,
            rendererOptions: {
                barWidth: 50,
            }
        },
        axesDefaults: {
            tickRenderer: $.jqplot.CanvasAxisTickRenderer,
			tickOptions: {
                fontFamily: 'Georgia',
                fontSize: '10pt',
                fontWeight: 'Bold',
                angle: -0
            }
        },
        axes: {
            xaxis: {
                renderer: $.jqplot.CategoryAxisRenderer,
            },
            yaxis: {
				// min:0,
                // max:100,
                tickOptions: {
                    formatString: '%d'
                },
            }
        },
			canvasOverlay: {
              show: true,
              objects: [
				{horizontalLine: {
                    name: 'pebbles',
					y: parseInt(tlo_clo_map_opp_val),
                    lineWidth: 1,
                    xOffset: 0,
                    color: 'green',
                    shadow: false
                }}
				]
            },
        highlighter: {
            show: true,
            showLabel: true,
            tooltipAxes: 'y',
            sizeAdjust: 7.5, tooltipLocation: 'ne'
        },
        legend: {
            show: true,
            placement: 'outside',
            labels: ticks
        },
    });
}
///	


// Function is used to insert the curriculum id onto an hidden input field.
function fetch_crclm()
{
    var crclmSelect = document.getElementById("dept_id1");
    var selectedcrclm = crclmSelect.options[crclmSelect.selectedIndex].text
    document.getElementById("curriculum_id").value = selectedcrclm;
}

function generate_pdf()
{
    var cloned_piechart = $('#chart1').clone().html();
    var cloned_mapping = $('#chart1b').clone().html();
    var cloned_crclm_level = $('#state_grid').clone().html();
    var cloned_course_level = $('#course_level_state_grid').clone().html();
    $('#pdf_cloned_piechart').val(cloned_piechart);
    $('#pdf_cloned_mapping').val(cloned_mapping);
    $('#pdf_cloned_crclm_level').val(cloned_crclm_level);
    $('#pdf_cloned_course_level').val(cloned_course_level);
    $('#form_id').submit();
}

