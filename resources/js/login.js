require('./bootstrap')
import { createApp } from 'vue'
import Login from './components/Login.vue'

const loginPage = createApp(Login)
loginPage.mount("#login")