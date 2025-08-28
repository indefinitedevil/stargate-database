function toggleHideable() {
    jQuery('.hideable, #toggle-hideable svg').each(function() {
        jQuery(this).toggleClass('hidden');
    });
}
