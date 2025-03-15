<template>
    <div class="dashboard-item">
        <h3 class="block-label">Статистика</h3>
        <template v-if="isLoading">
            <AppLoader :isSectionLoader="true"/>
        </template>
        <div  v-if="!isLoading" class="info-item__container">
            <div 
                v-for="option in statisticOptions" 
                :key="option.type" 
                class="info-item__row"
            >
                <span class="info-item__label">{{ option.title }}</span>
                <span class="info-item__dots"></span>
                <span class="info-item__value">{{ statistics?.[option.type] ?? '—' }}</span>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref } from "vue";
import { useRoute } from "vue-router";
import AppLoader from "../ui/AppLoader.vue";
import axios from "axios";

const statisticOptions = ref([
    { type: "stages_count", title: "Количество соревнований" },
    { type: "races_count", title: "Количество гонок" },
    { type: "top3_count", title: "ТОП-3" },
    { type: "win_rate", title: "Процент побед в гонках" },
    { type: "wins_stages", title: "Побед в регате" },
    { type: "avg_place", title: "Средний результат" },
]);

const route = useRoute();
const statistics = ref(null);
const isLoading = ref(true);

onMounted(async () => {
    try {
        const response = await axios.get(`/api/user/${route.params.id}/statistics`);
        statistics.value = response.data;ƒ
    } catch (error) {
        console.error("Ошибка при загрузке статистики:", error);
        isLoading.value = false;
    }
});
</script>

<style scoped>
.dashboard-item {
    min-height: 271px;
}
</style>
