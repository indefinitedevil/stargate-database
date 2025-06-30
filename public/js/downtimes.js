jQuery('[id^="development_action_"]').on('change', function () {
    let id = jQuery(this).attr('id').split('_').pop();
    jQuery('[id^="development_skill_' + id + '"]').html(jQuery('#ds_' + id + '_' + jQuery(this).val()).html());
    if (jQuery(this).val() == 4) { // Mission
        jQuery('#da_' + id + '_notes').removeClass('hidden');
    } else {
        jQuery('#da_' + id + '_notes').addClass('hidden');
    }
    if (jQuery(this).val() == 2) { // Teaching
        jQuery('#da_' + id + '_teaching').removeClass('hidden');
    } else {
        jQuery('#da_' + id + '_teaching').addClass('hidden');
    }
});
jQuery('[id^="research_action_"]').on('change', function () {
    let id = jQuery(this).attr('id').split('_').pop();
    if (jQuery(this).val() == 6) { // Upkeep 2
        jQuery('#upkeep_skill_' + id).removeClass('hidden');
        jQuery('#research_project_' + id).addClass('hidden');
        jQuery('#research_project_' + id + '_notes').addClass('hidden');
    } else if (jQuery(this).val() == 5) { // Research
        jQuery('#upkeep_skill_' + id).addClass('hidden');
        jQuery('#research_project_' + id).removeClass('hidden');
        if (jQuery('#research_project_' + id).length > 1) {
            jQuery('#research_project_' + id + '_notes').removeClass('hidden');
        }
    }
});
