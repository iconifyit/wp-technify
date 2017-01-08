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

                if (response.class == 'notice-sucess')

                console.log( response );

                $("#technify-notice").remove();

                var $container = $("#technify-wrap");

                // id="setting-error-settings_updated" class="updated settings-error notice is-dismissible"

                var $notice = $("<div/>");
                $notice.attr('id', 'technify-notice');
                $notice.addClass('notice');
                $notice.addClass('updated');
                $notice.addClass('settings-error');
                $notice.addClass('is-dismissible');
                $notice.addClass(response.class);
                var $p = $('<p/>').append($('<strong/>').text(response.text));
                $notice.append($p);
                var $button = $('<button/>');
                $button.attr('type', 'button');
                $button.addClass('notice-dismiss');
                $button.append($('<span/>').addClass('screen-reader-text').text('Dismiss this notice.'));
                $notice.append($button);
                $container.prepend($notice);

                var $notice = $("#technify-notice");
                var $button = $('button', $notice);
                $button.on('click', function(e) {
                    e.preventDefault();
                    $notice.fadeOut('200', function() {
                        $notice.remove();
                    });
                });
            });
        });
    });

})( jQuery );

/*
 <div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible">
    <p><strong>Settings saved.</strong></p>
    <button type="button" class="notice-dismiss">
        <span class="screen-reader-text">Dismiss this notice.</span>
    </button>
 </div>
 */