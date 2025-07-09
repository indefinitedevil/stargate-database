const ACTION_TRAINING = '1';
const ACTION_TEACHING = '2';
const ACTION_UPKEEP = '3';
const ACTION_MISSION = '4';
const ACTION_RESEARCH = '5';
const ACTION_UPKEEP_2 = '6';

jQuery('[id^="development_action_"]').on('change', function () {
    let id = jQuery(this).attr('id').split('_').pop();
    jQuery('[id^="development_skill_' + id + '"]').html(jQuery('#ds_' + id + '_' + jQuery(this).val()).html());
    if (jQuery(this).val() === ACTION_MISSION) { // Mission
        jQuery('#da_' + id + '_notes').removeClass('hidden');
    } else {
        jQuery('#da_' + id + '_notes').addClass('hidden');
    }
    if (jQuery(this).val() === ACTION_TEACHING) { // Teaching
        jQuery('#da_' + id + '_teaching').removeClass('hidden');
    } else {
        jQuery('#da_' + id + '_teaching').addClass('hidden');
    }
});

jQuery('[id^="research_action_"]').on('change', function () {
    let id = jQuery(this).attr('id').split('_').pop();
    if (jQuery(this).val() === ACTION_UPKEEP_2) { // Upkeep 2
        jQuery('#upkeep_skill_' + id).removeClass('hidden');
        jQuery('#research_project_' + id).addClass('hidden');
        jQuery('#research_action_' + id + '_notes').addClass('hidden');
    } else if (jQuery(this).val() === ACTION_RESEARCH) { // Research
        jQuery('#upkeep_skill_' + id).addClass('hidden');
        jQuery('#research_project_' + id).removeClass('hidden');
        if (jQuery('#research_project_' + id + ' option').length > 1) {
            jQuery('#research_action_' + id + '_notes').removeClass('hidden');
        }
    }
});

function toggleVisibility(id) {
    let element = document.getElementById(id);
    if (element.classList.contains('hidden')) {
        element.classList.remove('hidden');
    } else {
        element.classList.add('hidden');
    }
}
