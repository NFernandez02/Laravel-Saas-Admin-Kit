import { defineStore } from 'pinia'
import { me } from '../services/userService'

export const useAuthStore = defineStore('auth', {
    state: () => ({
        token: localStorage.getItem('token'),
        challengeToken: localStorage.getItem('challenge_token'),
        user: null
    }),

    getters: {
        isAuthenticated: (state) => !!state.token,
    },

    actions: {
        setToken(token) {
            this.token = token

            localStorage.setItem(
                'token',
                token
            )
        },
        setChallenge(challengeToken) {
            this.challengeToken = challengeToken

            localStorage.setItem(
                'challenge_token',
                challengeToken
            )
        },

        clearAuth() {
            this.token = null
            this.user = null

            localStorage.removeItem('token')
            localStorage.removeItem('challenge_token')
        },

        clearChallenge() {
            this.challengeToken = null
            localStorage.removeItem('challenge_token')
        },

        setUser(user) {
            this.user = user
        },

        async fetchUser(){
            try {
                const user = await me()

                this.user = user
            } catch {
                this.clearAuth()
            }
        },
    },

})