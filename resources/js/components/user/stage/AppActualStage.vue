<template>
    <AppUserStage
        v-for="stage in stages"
        :key="stage.id"
        :stage="stage"
    ></AppUserStage>
</template>

<script>
import AppUserStage from "@/components/user/stage/AppUserStage.vue";
import {onMounted, ref} from "vue";

export default {
    name: "AppActualStage",
    components: {
        AppUserStage
    },
    setup() {

        const stages = ref();

        onMounted(async ()=> {
            try {
                const response = await axios.get('/api/stage/actual');
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
