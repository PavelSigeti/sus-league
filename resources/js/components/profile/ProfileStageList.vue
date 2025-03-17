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
    display: flex;
    flex-wrap: wrap;
    gap: 30px;
}

.error-text {
    color: red;
    font-weight: bold;
    text-align: center;
    margin-top: 20px;
}

.empty-text {
    color: #777;
    font-weight: 500;
    text-align: center;
    margin-top: 20px;
}

@media (max-width: 1045px) {
    .stage-list__container {
        flex-wrap: nowrap;
        flex-direction: column;
    }
}
</style>
