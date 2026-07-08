import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'

import './style.css'
import { useAuthStore } from './stores/authStore.js'

const pinia = createPinia()

const app = createApp(App)
app.use(pinia)
app.use(router)

const auth = useAuthStore()

if(auth.token){
    auth.fetchUser()
}

app.mount('#app')
