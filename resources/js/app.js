require('./bootstrap')
import { createApp } from 'vue'
import root from './root.vue'

const app = createApp(root)
app.mount("#app")