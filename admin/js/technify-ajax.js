/**
 * Created by scott on 1/6/17.
 */

;(function( $ ) {
    'use strict';

    $(function() {

        $('.technify-compressor').on('click', function(e) {

            e.preventDefault();

            var data = {
                'action': $(this).data('action'),
                'security': $('#technify-nonce').val()
            };

            console.log( data );

            jQuery.post(ajax_object.ajax_url, data, function(response) {

                response = JSON.parse(response);

                console.log( response );

                var $notice = $("div.notice");

                $('p', $notice).text( response.text );
                $notice.removeClass('notice-success');
                $notice.removeClass('notice-error');
                $notice.addClass( response.class );

            });
        });
    });

})( jQuery );