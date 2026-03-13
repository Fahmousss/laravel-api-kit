import './globals/theme.js'; /* By Sheaf.dev */ 

import './bootstrap';
import Alpine from 'alpinejs';
import persist from '@alpinejs/persist';
import anchor from '@alpinejs/anchor';
import focus from '@alpinejs/focus';

window.Alpine = Alpine;
Alpine.plugin(persist);
Alpine.plugin(anchor);
Alpine.plugin(focus);
Alpine.start();