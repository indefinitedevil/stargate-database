function showSkillDescription(skillId) {
    let skills = document.querySelectorAll('.skill-description');
    skills.forEach(function (skill) {
        skill.classList.add('hidden');
    });
    let skill = document.getElementById('skill-description-' + skillId);
    skill.classList.remove('hidden');
}

function showBackgroundDescription(backgroundId) {
    let backgrounds = document.querySelectorAll('.background-description');
    backgrounds.forEach(function (background) {
        background.classList.add('hidden');
    });
    let background = document.getElementById('background-description-' + backgroundId);
    background.classList.remove('hidden');
}

function toggleVisibility(id) {
    let element = document.getElementById(id);
    if (element.classList.contains('hidden')) {
        element.classList.remove('hidden');
    } else {
        element.classList.add('hidden');
    }
}

function generateRandomNames() {
    let randomNames = document.getElementById('randomNames');
    randomNames.innerHTML = '';
    let nameHtml = '<ul class="list-inside list-disc">';
    for (let i = 0; i < 5; i++) {
        nameHtml += '<li>' + window.generateName(0) + '</li>';
    }
    nameHtml += '</ul><ul class="list-inside list-disc">';
    for (let i = 0; i < 5; i++) {
        nameHtml += '<li>' + window.generateName(1) + '</li>';
    }
    nameHtml += '</ul>';
    randomNames.innerHTML = nameHtml;
}
