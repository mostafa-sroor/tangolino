
function scroll_to_class(element_class, removed_height) {
	var scroll_to = $(element_class).offset().top - removed_height;
	if($(window).scrollTop() != scroll_to) {
		$('html, body').stop().animate({scrollTop: scroll_to}, 0);
	}
}

function bar_progress(progress_line_object, direction) {
	var number_of_steps = progress_line_object.data('number-of-steps');
	var now_value = progress_line_object.data('now-value');
	var new_value = 0;
	if(direction == 'right') {
		new_value = now_value + ( 100 / number_of_steps );
	}
	else if(direction == 'left') {
		new_value = now_value - ( 100 / number_of_steps );
	}
	progress_line_object.attr('style', 'width: ' + new_value + '%;').data('now-value', new_value);
}

jQuery(document).ready(function() {
	
    /*
        Fullscreen background
    */
    $.backstretch("assets/img/backgrounds/1.jpg");
    
    $('#top-navbar-1').on('shown.bs.collapse', function(){
    	$.backstretch("resize");
    });
    $('#top-navbar-1').on('hidden.bs.collapse', function(){
    	$.backstretch("resize");
    });
    
    /*
        Form
    */
    $('.f1 fieldset:first').fadeIn('slow');
    
    $('.f1 input[type="text"], .f1 input[type="password"], .f1 textarea').on('focus', function() {
    	$(this).removeClass('input-error');
    });
    
    // next step
    $('.f1 .btn-next').on('click', function() {
		var id = $(this).attr('id');
    	var parent_fieldset = $(this).parents('fieldset');
    	var next_step = true;
    	// navigation steps / progress steps
    	var current_active_step = $(this).parents('.f1').find('.f1-step.active');
    	var progress_line = $(this).parents('.f1').find('.f1-progress-line');
    	var is_title_exists = false;
		var is_checkoutlink_exists = false;

		if(id == 'second_next'){
			// fields validation
			parent_fieldset.find('select[name^="fields"]').each(function() {
				if($(this).val() == 'title' && !is_title_exists){
					is_title_exists = true;
				}
				if($(this).val() == 'checkout_link' && !is_checkoutlink_exists) {
					is_checkoutlink_exists = true;
				}
			});

			if(!is_title_exists){
				next_step = false
				$('.top-content').prepend('<div class="alert alert-warning alert-dismissable" style="text-align:left">' +
					'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Warning : </strong> ' +
					'Title field is required ...'  + '</div>');
			}

			if(!is_checkoutlink_exists){
				next_step = false
				$('.top-content').prepend('<div class="alert alert-warning alert-dismissable" style="text-align:left">' +
					'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Warning : </strong> ' +
					'Checkout link field is required ...'  + '</div>');
			}
		}

    	// fields validation
    	
    	if( next_step ) {
    		parent_fieldset.fadeOut(400, function() {
				$(".alert-warning").remove();
				$(".alert-success").remove();
    			// change icons
    			current_active_step.removeClass('active').addClass('activated').next().addClass('active');
    			// progress bar
    			bar_progress(progress_line, 'right');
    			// show next step
	    		$(this).next().fadeIn();
	    		// scroll window to beginning of the form
    			scroll_to_class( $('.f1'), 20 );
	    	});
    	}
    	
    });
    
    // previous step
    $('.f1 .btn-previous').on('click', function() {

		$(".alert-warning").remove();
		$(".alert-success").remove();


		// navigation steps / progress steps
    	var current_active_step = $(this).parents('.f1').find('.f1-step.active');
    	var progress_line = $(this).parents('.f1').find('.f1-progress-line');
    	
    	$(this).parents('fieldset').fadeOut(400, function() {
    		// change icons
    		current_active_step.removeClass('active').prev().removeClass('activated').addClass('active');
    		// progress bar
    		bar_progress(progress_line, 'left');
    		// show previous step
    		$(this).prev().fadeIn();
    		// scroll window to beginning of the form
			scroll_to_class( $('.f1'), 20 );
    	});
    });
    
    // submit
    $('.f1').on('submit', function(e) {
    	
    	// fields validation
    	$(this).find('input[type="text"], input[type="password"], textarea').each(function() {
    		if( $(this).val() == "" ) {
    			e.preventDefault();
    			$(this).addClass('input-error');
    		}
    		else {
    			$(this).removeClass('input-error');
    		}
    	});
    	// fields validation
    	
    });
    
    
});
