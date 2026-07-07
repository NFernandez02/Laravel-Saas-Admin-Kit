import { createRouter, createWebHistory } from "vue-router";

import LoginPage from '../pages/authentication/LoginPage.vue'
import ChallengePage from "../pages/authentication/ChallengePage.vue";
import DashboardPage from "../pages/user/DashboardPage.vue";

const routes = [
    {
        path: '/',
        component: LoginPage,
    },
    {
        path: '/challenge',
        component: ChallengePage,
    },
    {
        path: '/dashboard',
        component: DashboardPage
    }
]

export default createRouter({
    history: createWebHistory(),
    routes,
})