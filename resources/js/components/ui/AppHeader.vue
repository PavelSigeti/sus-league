<template>
    <header class="dashboard-header">
        <div class="mobile-header">
            <div class="mobile-header__left">
                <div class="sidebar-bars" @click="sidebar"><div class="bars"></div></div>
                <router-link class="homepage-link" to="/dashboard"><img src="@/static/logo.svg" alt="vrisc logo" class="logo"></router-link>
            </div>
            <div class="header-content">
                <div class="btn btn-border support-btn" @click="support = true; bodyOverflow(false)">Обратная связь</div>
                <div v-if="false" class="header-notification">
                    <span class="header-notification__counter">1</span>
                    <i class="ri-notification-2-fill"></i>
                </div>
            </div>
        </div>
        <div class="header-container">
            <h1><slot /></h1>
            <div class="header-content hide767">
                <div class="btn btn-border support-btn" @click="support = true; bodyOverflow(false)">Обратная связь</div>
                <div v-if="false" class="header-notification">
                    <span class="header-notification__counter">1</span>
                    <i class="ri-notification-2-fill"></i>
                </div>
            </div>
        </div>
    </header>
    <keep-alive>
        <AppSupport v-if="support" @close="support = false; bodyOverflow(true)" />
    </keep-alive>
</template>

<script>
import { ref } from 'vue';
import bodyOverflow from "@/utils/bodyOverflow.js";
import AppSupport from "./AppSupport.vue";
import {useStore} from "vuex";

export default {
    name: "AppHeader.vue",
    components: {
        AppSupport
    },
    setup() {
        const support = ref(false);

        const store = useStore();

        const sidebar = () => {
            store.dispatch('sidebar/toggleStatus');
        };

        return {
            support, bodyOverflow, sidebar,
        };
    }
}
</script>
