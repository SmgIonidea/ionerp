/*!
	* Description: Custom js for applicable through out the application.
	* Author: Varun V. K
*/

var mouseOutOfView;
var mouseYPosition;
 
 jQuery(window).mouseout(
         function(e){
             if(!e) e=window.event;
             var relTarg = e.relatedTarget || e.toElement;
            
            if(relTarg != undefined && relTarg.nodeName != 'HTML'){                
                mouseOutOfView = false;
            }else{
                mouseYPosition = e.pageY;
                mouseOutOfView = true;
            }

        }
);

$(window).on('beforeunload', function(e) {
	if((window.innerWidth != undefined && window.innerWidth <= 0) || 
                (mouseYPosition != undefined && mouseOutOfView && 
                        mouseYPosition <=0 && mouseYPosition)){
            window.location.href = $('#get_base_url').val()+"logout/index";    
           return;
        }
	//window.location.href = $('#get_base_url').val()+"logout/index";
});

window.history.forward();
function noBack() { window.history.forward(); }