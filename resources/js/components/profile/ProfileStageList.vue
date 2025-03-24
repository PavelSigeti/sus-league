<template>
    <div class="dashboard-item">
        <h3 class="block-label">История соревнований</h3>

        <template v-if="isLoading">
            <AppLoader :isSectionLoader="true" />
        </template>

        <template v-else>
            <p v-if="errorMessage" class="second-text">{{ errorMessage }}</p>
            <p v-else-if="stages.length === 0" class="second-text">Нет данных</p>
            <div v-else class="stage-list__container">
                <StageItem v-for="stage in stages" :key="stage.id" :stage="stage" />
            </div>
        </template>
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
const errorMessage = ref(null);

onMounted(async () => {
    try {
        const response = await axios.get(`/api/user/${route.params.id}/stages`);
        stages.value = response.data.length ? response.data : [];
    } catch (error) {
        console.error("Ошибка загрузки соревнований:", error);
        errorMessage.value = "Ошибка загрузки данных";
    } finally {
        isLoading.value = false;
    }
});
</script>

<style scoped>
.stage-list__container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(310px, 1fr));
    grid-auto-rows: 205px;
    gap: 30px;
}

</style>
