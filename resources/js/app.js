import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

import { fullName } from 'full-name-generator';

window.generateName = function(gender) {
    return fullName('GB', gender)
}
