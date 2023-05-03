require('./bootstrap')
import { createApp } from 'vue'
import Test from './components/Test'

const app = createApp(Test)
app.mount("#app")