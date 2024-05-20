<template>
    <div class="stage-container">
        <AppDashStages
            v-for="stage in stages"
            :key="stage.id"
            :stage="stage"
        ></AppDashStages>
    </div>
</template>

<script setup>
import AppDashStages from "@/components/user/dash/AppDashStages.vue";
import {onMounted, ref} from "vue";

const stages = ref();

onMounted(async ()=> {
    try {
        const response = await axios.get('/api/stage/actual');
        stages.value = response.data;
    } catch (e) {
        console.log(e.message);
    }
});

</script>

<style scoped>
.stage-container {
    display: flex;
    flex-direction: column;
    gap: 15px;
}
</style>
