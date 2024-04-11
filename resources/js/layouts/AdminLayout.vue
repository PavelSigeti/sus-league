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
import TheNotification from "@/components/ui/TheNotification.vue";
import TheSidebar from "../components/TheSidebar.vue";
import { useStore } from 'vuex';
import { computed } from 'vue';

export default {
    name: "AdminLayout",
    components: {
        TheNotification, TheSidebar,
    },
    setup() {
        const store = useStore();

        const message = computed(() => store.getters['notification/message']);
        const type = computed(() => store.getters['notification/type']);


        return {
            message, type,
        }
    }
}
</script>

<style scoped lang="scss">
     :deep(){
            @import '../static/scss/admin.scss';
    }
</style>
