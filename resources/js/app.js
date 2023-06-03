require('./bootstrap')
import { createApp } from 'vue'
import root from './root.vue'
import Login from './components/Login.vue'

const app = createApp(root)
app.mount("#app")

const loginPage = createApp(Login)
loginPage.mount("#login")