<?php
/**
* Description	:	Generic Footer View file of the application contains Application footer statement.
*					
*Created		:	25-03-2013. 
*		  
* Modification History:
* Date				Modified By					Description
* 20-08-2013		Abhinay B.Angadi        Added file headers & indentations.
* 13-09-2015		Arihant Prasad			Feedback link has been included
* 27-01-2016		Arihant Prasad			Language option has been included
--------------------------------------------------------------------------------------------------------
*/
?>
		
		<!-- extra padding on top of footer -->
		<div style="padding-bottom:40px;"></div>
		<!-- Footer -->
		<footer class="footer-color footer_settings">
			<div class="container-fluid footer-p">
				<div class="span5">
					
				</div>
				<div class="span5" style="margin-top:4px;">
					IonCUDOS v5.2 - Copyright &copy; 2014 by IonIdea.
				</div>
				
				<div class="dropup span2" style="margin-top:4px;">
					<!--<div id="google_translate_element"></div><script type="text/javascript">
						function googleTranslateElementInit() {
						  new google.translate.TranslateElement({pageLanguage: 'en', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
						}
						</script><script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>-->
				</div>
				
				<a style="color: white; text-decoration:none; position: relative; top: 4px;" class="span1" target="_blank" href="http://www.ioncudos.com/?page_id=237&msg_id=1&msg=<?php if(!empty($title)) { echo base64_encode($title); } ?>"> Feedback </a>
			</div>
		</footer>
		
		<script>
			var language_data = 0;
		
			/* $(function() {
					//script to place footer at the bottom
					if($("body").height()<$(window).height()) {
						$("body").css("height", $(window).height());
					}
					
					$(window).resize(function() {
						if($("body").height()<$(window).height()) {
							$("body").css("height", $(window).height());
						}
					});
				}); */
		</script>
		
		<script>
$(function(){

 if($.cookie('cookie_class') == 'show' || $.cookie('cookie_class') == null){
	$('.dropdown_menu').each(function(){
			$(this).removeClass('dropdown');
		}); 
	$('.ul_dropdown').each(function(){
		$(this).removeClass('dropdown-menu');
	});
    $('.toggle_side_menu').removeClass('show').addClass('show');
    $('div .bs-docs-sidebar').removeClass( "display_none" );
    $('div.bs-docs-sidebar').nextAll('.span10:first').removeClass( "full_width" );
 }else{
	$('.dropdown_menu').each(function(){
			$(this).addClass('dropdown');
		});
		$('.ul_dropdown').each(function(){
			$(this).addClass('dropdown-menu');
		});
    $('.toggle_side_menu').removeClass('show').addClass('hide');
    $('div .bs-docs-sidebar').addClass( "display_none" );
    $('div.bs-docs-sidebar').nextAll('.span10:first').addClass( "full_width" );
	
	$(function(){
			$(".dropdown").hover(            
					function() {
						$('.dropdown-menu', this).stop( true, true ).fadeIn("fast");
						$(this).toggleClass('open');
						$('b', this).toggleClass("caret caret-up");                
					},
					function() {
						$('.dropdown-menu', this).stop( true, true ).fadeOut("fast");
						$(this).toggleClass('open');
						$('b', this).toggleClass("caret caret-up");                
					});
			});
	
 }
});
$('.toggle_side_menu').on('click',function(){
    if($(this).hasClass('show')){
		$('.dropdown_menu').each(function(){
			$(this).addClass('dropdown');
		});
		$('.ul_dropdown').each(function(){
			$(this).addClass('dropdown-menu');
		});
		$(this).removeClass('show').addClass('hide');
        $.removeCookie('cookie_class','', {expires: 90, path: '/'});
        $.cookie('cookie_class','hide', {expires: 90, path: '/'});
		$('div .bs-docs-sidebar').addClass( "display_none" );
		$('div.bs-docs-sidebar').nextAll('.span10:first').addClass( "full_width" );
		
		$(function(){
			$(".dropdown").hover(            
					function() {
						$('.dropdown-menu', this).stop( true, true ).fadeIn("fast");
						$(this).toggleClass('open');
						$('b', this).toggleClass("caret caret-up");                
					},
					function() {
						$('.dropdown-menu', this).stop( true, true ).fadeOut("fast");
						$(this).toggleClass('open');
						$('b', this).toggleClass("caret caret-up");                
					});
			});
		
	}else{
        $('.dropdown_menu').each(function(){
			$(this).removeClass('dropdown');
		}); 
		$('.ul_dropdown').each(function(){
			$(this).removeClass('dropdown-menu');
		});
		$(this).removeClass('hide').addClass('show');
        $.removeCookie('cookie_class','', {expires: 90, path: '/'});
        $.cookie('cookie_class','show', {expires: 90, path: '/'});
		$('div .bs-docs-sidebar').removeClass( "display_none" );
		$('div.bs-docs-sidebar').nextAll('.span10:first').removeClass( "full_width" );
		
		$(function(){
			$(".dropdown").hover(            
					function() {
						$('.dropdown-menu', this).stop( false, false ).fadeIn("fast");
							$(this).toggleClass('open');
							$('b', this).toggleClass("caret caret-up");                
					},
					function() {
						$('.dropdown-menu', this).stop( false, false ).fadeOut("fast");
							$(this).toggleClass('open');
							$('b', this).toggleClass("caret caret-up");                
					});
			});
		
	}
});

		$(function(){
			$(".dropdown").hover(            
					function() {
						$('.dropdown-menu', this).stop( true, true ).fadeIn("fast");
						$(this).toggleClass('open');
						$('b', this).toggleClass("caret caret-up");                
					},
					function() {
						$('.dropdown-menu', this).stop( true, true ).fadeOut("fast");
						$(this).toggleClass('open');
						$('b', this).toggleClass("caret caret-up");                
					});
			});
		
	$(window).scroll(function () {
		var iCurScrollPos = $(this).scrollTop();
		if (iCurScrollPos > 80) {
			//Scrolling Down
			$('#main_navbar').addClass('navbar-fixed-top').addClass('navbar_color_change');
		} else {
		   //Scrolling Up
		   $('#main_navbar').removeClass('navbar-fixed-top').removeClass('navbar_color_change');
		}
		iScrollPos = iCurScrollPos;
	});
</script>

	</body>
</html>
