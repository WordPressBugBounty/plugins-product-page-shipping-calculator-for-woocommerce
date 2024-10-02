(function ($) {
    'use strict';

    function showWhenValue($show, $when, $value) {
        var show = jQuery($show);
        jQuery($when).on('change', function () {
            var val = jQuery(this).val();
            if (val == $value) {
                show.insertAfter($when);
            } else {
                show.remove();
            }
        });
        jQuery($when).trigger('change');
    }

    jQuery(function ($) {
        showWhenValue('#hidden-shortcode-msg', '#pi_ppscw_calc_position', 'shortcode');
    });

})(jQuery);