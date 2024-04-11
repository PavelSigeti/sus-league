<template>
    <div class="modal" v-if="sidebar" @click="close"></div>
    <div :class="['sidebar-container', {'sidebar-container__show': sidebar}]">
        <div class="close" @click="close"></div>
        <div class="sidebar">
            <div class="sidebar-main">
                <AppUser />
                <the-navbar></the-navbar>
            </div>
            <div class="sidebar-bottom">
                <div class="menu-item menu-item__exit" @click="logout"><a @click.prevent="logout" href="#"><img src="@/static/exit.svg" alt="exit"><span>Выйти</span></a></div>
            </div>
        </div>
    </div>
</template>

<script>
import TheNavbar from "../components/TheNavbar.vue";
import AppUser from '../components/ui/AppUser.vue';
import {computed, watch} from "vue";
import {useStore} from "vuex";
import {useRouter} from "vue-router";

export default {
    name: "TheSidebar",
    components: {
        TheNavbar, AppUser,
    },
    setup() {
        const store = useStore();
        const router = useRouter();

        const sidebar = computed(()=>store.getters['sidebar/status']);
        const close = () => {
            store.dispatch('sidebar/toggleStatus');
        };

        watch(sidebar, () => {
            router.beforeEach(() => {
                store.dispatch('sidebar/toggleStatus');
            });
        });

        const logout = () => {
            store.dispatch('auth/logout');
        };
        return {
            logout, sidebar, close,
        }
    }
}
</script>

<style scoped>

</style>
