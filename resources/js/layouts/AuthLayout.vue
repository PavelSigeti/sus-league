<template>
    <TheNotification v-if="message" :payload="{message, type}"></TheNotification>
    <div class="grid-container">
        <TheSidebar />
        <div class="main-container">
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
import TheSidebar from "../components/TheSidebar.vue";
import TheNotification from "../components/ui/TheNotification.vue";
import {useStore} from "vuex";
import {computed, ref} from "vue";

export default {
    name: "AuthLayout",
    components: {
        TheNotification, TheSidebar,
    },
    setup() {
        const store = useStore();

        const close = () => {
            store.dispatch('notification/clearMessage');
        }
        const message = computed(() => store.getters['notification/message']);
        const type = computed(() => store.getters['notification/type']);


        return {
            close, message, type,
        }
    }
}
</script>

<style scoped>

</style>
