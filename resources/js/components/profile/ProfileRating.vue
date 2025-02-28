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
                            <p >{{ error[section.key] }}</p>
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

const ratingSections = ref([
    { key: "statistics", title: "Статистика" },
    { key: "personal", title: "Личный зачет" },
    { key: "team", title: "Командный зачет" },
    { key: "university", title: "Университетский зачет" }
]);

// Тестовые данные
const mockData = {
    statistics: [
        { name: "Всего гонок", score: 10 },
        { name: "Среднее место в гонке", score: 4.5 },
        { name: "Выигранных гонок", score: 3 },
        { name: "Всего регат", score: 2 }
    ],
    personal: [
        { name: "1. Phew", score: 1201 },
        { name: "2. Waprion", score: 1105 },
        { },
        { name: "102. Applee", score: 10 }
    ],
    team: [
        { name: "1. Red Storm", score: 3200 },
        { name: "2. Blue Waves", score: 2890 },
        { },
        { name: "50. Green Sail", score: 1450 }
    ],
    university: [
        { name: "1. MIT", score: 5100 },
        { name: "2. Harvard", score: 4800 },
        { },
        { name: "30. Yale", score: 2500 }
    ]
};

const ratings = ref({});
const error = ref({});
const isLoading = ref(true);

const fetchMockData = () => {
    setTimeout(() => {
        ratingSections.value.forEach((section) => {
            if (Math.random() < 0.1) {
                error.value[section.key] = "Ошибка загрузки данных";
            } else {
                ratings.value[section.key] = mockData[section.key] || [];
            }
        });
        isLoading.value = false;
    }, 1000 + Math.random() * 2000);
};

onMounted(() => {
    fetchMockData();
});
</script>

<style scoped>
.dashboard-item {
    min-height: 475px;
}

.info-item__container {
    min-height: 114px;
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
