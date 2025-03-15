<template>
    <div class="dashboard-item">
        <h3 class="block-label">Рейтинг</h3>
        <template v-if="isLoading">
            <AppLoader :isSectionLoader="true"/>
        </template>
        <div v-if="!isLoading" class="grid-section">
            <div v-for="section in ratingSections" :key="section.key" class="grid-item">
                <div class="info-item__container">
                    <h4 class="section-name">{{ section.title }}</h4>
                    <template v-if="error[section.key]">
                        <p>{{ error[section.key] }}</p>
                    </template>
                    <template v-else-if="ratings[section.key]?.length">
                        <div v-for="(item) in ratings[section.key]" :key="item.name">
                            <div class="info-item__row">
                                <span v-if="item.name" class="info-item__label">{{ item.name }}</span>
                                <span class="info-item__dots"></span>
                                <span v-if="item.score" class="info-item__value">{{ item.score }}</span>
                            </div>
                        </div>
                    </template>
                    <template v-else>
                        <p class="empty-text">Нет данных</p>
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import AppLoader from "../ui/AppLoader.vue";
import { ref, onMounted } from "vue";
import { useRoute } from "vue-router";
import axios from 'axios';

const route = useRoute();

const ratingSections = ref([
    { key: "statistics", title: "Статистика" },
    { key: "personal", title: "Личный зачет" },
    { key: "team", title: "Командный зачет" },
    { key: "university", title: "Университетский зачет" }
]);

const ratings = ref({
    statistics: [],
    personal: [],
    team: [],
    university: []
});
const error = ref({
    statistics: null,
    personal: null,
    team: null,
    university: null
});
const isLoading = ref(true);

onMounted( async () => {
    try {
        const [statisticsResponse, personalResponse, teamResponse, universityResponse] = await Promise.all([
            axios.get(`/api/user/${route.params.id}/rating`),
            axios.get(`/api/user/${route.params.id}/rating/personal`),
            axios.get(`/api/user/${route.params.id}/rating/team`),
            axios.get(`/api/user/${route.params.id}/rating/university`)
        ]);
        ratings.value.statistics = statisticsResponse.data || [];
        ratings.value.personal = personalResponse.data || [];
        ratings.value.team = teamResponse.data || [];
        ratings.value.university = universityResponse.data || [];
        console.log(ratings.value)
    } catch (err) {
        console.error(err);
        error.value.statistics = "Ошибка загрузки данных для статистики";
        error.value.personal = "Ошибка загрузки данных для личного зачета";
        error.value.team = "Ошибка загрузки данных для командного зачета";
        error.value.university = "Ошибка загрузки данных для университетского зачета";
    } finally {
        isLoading.value = false;
    }
});
</script>

<style scoped>
.dashboard-item {
    min-height: 475px;
}

.info-item__container {
    min-height: 114px;
    max-height: 160px;
    overflow: hidden;
}

.grid-section {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 30px;
}

.section-name {
    font-family: Jost;
    font-size: 18px;
    font-style: normal;
    font-weight: 500;
    line-height: normal;
    color: #242424;
    margin-bottom: 5px;
}

@media (max-width: 992px) {
    .grid-section {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 767px) {
    .grid-section {
        gap: 12px;
    }
}
</style>
