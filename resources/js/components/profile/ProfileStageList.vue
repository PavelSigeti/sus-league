<template>
    <div class="dashboard-item">
        <h3 class="block-label">История соревнований</h3>
        <template v-if="isLoading">
            <AppLoader :isSectionLoader="true"/>
        </template>
        <div v-else class="stage-list__container">
            <StageItem 
                v-for="stage in stages" 
                :key="stage.id" 
                :stage="stage"
            />
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRoute } from "vue-router";
import AppLoader from "../ui/AppLoader.vue";
import StageItem from "./StageItem.vue";
import axios from "axios";

const route = useRoute();
const stages = ref([]);
const isLoading = ref(true);

onMounted(async () => {
    try {
        const response = await axios.get(`/api/user/${route.params.id}/stages`);
        stages.value = response.data;
    } catch (error) {
        console.error("Ошибка загрузки соревнований:", error);
    } finally {
        isLoading.value = false;
    }
});
</script>

<style scoped>
.stage-list__container {
    display: flex;
    flex-wrap: wrap;
    gap: 30px;
}

@media (max-width: 1045px) {
    .stage-list__container {
        flex-wrap: nowrap;
        flex-direction: column;
        
}
}
</style>
