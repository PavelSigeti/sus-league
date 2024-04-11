<template>
    <vue3-progress-bar></vue3-progress-bar>
    <component :is="layout + '-layout'" v-if="layout" />
</template>

<script>
import {useRoute} from "vue-router";
import {computed, onBeforeMount} from "vue";
import ClearLayout from "./layouts/ClearLayout.vue";
import HomePageLayout from "./layouts/HomePageLayout.vue";
import AuthLayout from "./layouts/AuthLayout.vue";
import AdminLayout from "./layouts/AdminLayout.vue";
import '@marcoschulte/vue3-progress/dist/index.css';

export default {
    name: "App",
    components: {
        ClearLayout, HomePageLayout,
        AuthLayout, AdminLayout
    },
    setup() {
        const route = useRoute();

        onBeforeMount(() => {

        });

        return {
            layout: computed(() => route.meta.layout),
        }
    }
}
</script>

