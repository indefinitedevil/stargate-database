function toggleAttended() {
    jQuery('.not-attended, #toggle-attended svg').each(function() {
        jQuery(this).toggleClass('hidden');
    });
}

function toggleBooked() {
    jQuery('.not-booked, #toggle-booked svg').each(function() {
        jQuery(this).toggleClass('hidden');
    });
}
