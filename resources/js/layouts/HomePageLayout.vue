<template>
    <TheNotification v-if="message" :payload="{message, type}"></TheNotification>
    <router-view></router-view>
</template>

<script>
import TheNotification from "@/components/ui/TheNotification.vue";
import {useStore} from "vuex";
import {computed} from "vue";

export default {
    name: "HomePageLayout",
    components: {
        TheNotification
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

<style scoped>

</style>
