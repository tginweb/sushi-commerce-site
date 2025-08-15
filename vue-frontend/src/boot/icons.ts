import {defineBoot} from '#q-app/wrappers';
import {App} from 'vue';
import 'vue3-carousel/carousel.css'

import {ICONS, IconsMap} from '@/assets/icons';

export default defineBoot(({app}: { app: App }) => {
    app.provide<IconsMap>('icons', ICONS)
})
