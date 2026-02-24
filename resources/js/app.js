import './bootstrap';
import TomSelect from 'tom-select';
import "tom-select/dist/css/tom-select.css";

import Alpine from 'alpinejs';

window.Alpine = Alpine;
window.onselect = TomSelect;

Alpine.start();
