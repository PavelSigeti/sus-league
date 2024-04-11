<template>
    <AppDashStages
        v-for="stage in stages"
        :key="stage.id"
        :stage="stage"
    ></AppDashStages>
</template>

<script>
import AppDashStages from "./AppDashStages.vue";
import {onMounted, ref} from "vue";

export default {
    name: "AppDashActualStages",
    components: {
        AppDashStages,
    },
    setup() {

        const stages = ref();

        onMounted(async ()=> {
            try {
                const response = await axios.get('/api/stage/actual/dashboard');
                stages.value = response.data;
            } catch (e) {
                console.log(e.message);
            }
        });

        return {
            stages
        };
    }
}
</script>

<style scoped>

</style>
