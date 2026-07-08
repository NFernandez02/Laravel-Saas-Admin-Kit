import { createRouter, createWebHistory } from "vue-router";

import LoginPage from '../pages/authentication/LoginPage.vue'
import ChallengePage from "../pages/authentication/ChallengePage.vue";
import DashboardPage from "../pages/user/DashboardPage.vue";
import { useAuthStore } from "../stores/authStore.js";

const routes = [
    {
        path: '/',
        component: DashboardPage,
        meta: {
            requiresAuth: true
        }
    },
    {   
        path: '/login',
        component: LoginPage,
        meta: {
            guestOnly: true
        },
    },
    {
        path: '/challenge',
        component: ChallengePage,
        meta: {
            requiresChallenge: true
        }
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

router.beforeEach((to) => {
    const auth = useAuthStore()

    if(to.meta.requiresAuth && !auth.isAuthenticated) {
        return '/login'
    }

    if(to.meta.guestOnly && auth.isAuthenticated){
        return '/'
    }
    if(to.meta.requiresChallenge){
        if(!auth.challengeToken){
            return '/'
        }
    }
})

export default router