$(function() {

    /*
        fade in blocks
    */
    $('.block').addClass('is--animating');

    setTimeout(function () { 
        $('.block').removeClass('is--animating');
    }, 500);


    /*
        show/hide mobile menu
    */
    $('.menu__toggle').click(function() {
        $('.menu__whole').toggleClass('is--open');
    });


    /*
    	focusing finder
    */

    $('.finder__display__input')
        .keyup(function() {

            if ($(this).val() == '') {
                $(this).closest('.finder').removeClass('is--focused');
                $('body, html').removeClass('is--unscrollable');
            }

            else {
                $(this).closest('.finder').addClass('is--focused');
                $('body, html').addClass('is--unscrollable');
            }
        });

    /*
    $('.finder__display__input')
	    .on('focus', function(){
	        $(this).closest('.finder').addClass('is--focused');
            $('body, html').addClass('is--unscrollable');
	    })
	    .on('blur', function(){
	        $(this).closest('.finder').removeClass('is--focused');
            $('body, html').removeClass('is--unscrollable');
	});
    */

});