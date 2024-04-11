<template>
    <AppHomeUserStage
        v-for="stage in stages"
        :key="stage.id"
        :stage="stage"
    ></AppHomeUserStage>
</template>

<script>
import AppHomeUserStage from "./AppHomeUserStage.vue";
import {onMounted, ref} from "vue";

export default {
    name: "AppHomeActualStage",
    components: {
        AppHomeUserStage
    },
    setup() {

        const stages = ref();

        onMounted(async ()=> {
            try {
                const response = await axios.get('/api/home/stage/actual');
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
