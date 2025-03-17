<template>
    <div class="dashboard-item">
        <h3 class="block-label">Рейтинг <div class="block-subtitle">сезон 2023/24</div></h3>

        <template v-if="isLoading">
            <AppLoader :isSectionLoader="true"/>
        </template>

        <div v-else class="grid-section">
            <div v-for="section in ratingSections" :key="section.key" class="grid-item">
                    <div :class="['info-item__container', { 'statistics-container': section.key === 'statistics' }]">
                    <h4 class="section-name">{{ section.title }}</h4>

                    <template v-if="errors[section.key]">
                        <p class="second-text">{{ errors[section.key] }}</p>
                    </template>

                    <template v-else-if="ratings[section.key]?.length">
                        <div v-for="item in ratings[section.key]" :key="item.name" 
                             :class="{ 'highlight-user': item.is_user }">
                            <div class="info-item__row">
                                <span v-if="item.name" class="info-item__label">{{ item.name }}</span>
                                <span class="info-item__dots"></span>
                                <span class="info-item__value">{{ item.score }}</span>
                            </div>
                        </div>
                    </template>

                    <template v-else>
                        <p class="second-text">Нет данных</p>
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from "vue";
import { useRoute, useRouter } from "vue-router";
import AppLoader from "../ui/AppLoader.vue";
import axios from "axios";

const route = useRoute();
const router = useRouter();

const ratingSections = ref([
    { key: "statistics", title: "Статистика", url: `/api/user/${route.params.id}/rating` },
    { key: "personal", title: "Личный зачет", url: `/api/user/${route.params.id}/rating/personal` },
    { key: "team", title: "Командный зачет", url: `/api/user/${route.params.id}/rating/team` },
    { key: "university", title: "Университетский зачет", url: `/api/user/${route.params.id}/rating/university` }
]);

const ratings = ref(Object.create(null));
const errors = ref(Object.create(null));
const isLoading = ref(true);

onMounted(async () => {
    try {
        const requests = ratingSections.value.map(section =>
            axios.get(section.url)
                .then(response => ({ key: section.key, data: response.data || [] }))
                .catch(() => ({ key: section.key, error: "Ошибка загрузки данных" }))
        );

        const results = await Promise.all(requests);

        for (const result of results) {
            if (result.error) {
                errors.value[result.key] = result.error;
            } else {
                ratings.value[result.key] = result.data;
            }
        }
    } catch (err) {
        console.error("Ошибка загрузки рейтинга:", err);
    } finally {
        isLoading.value = false;
    }

    setTimeout(() => {
        document.querySelectorAll(".info-item__container:not(.statistics-container)").forEach(el => {
            el.addEventListener("click", goToRatingPage);
            el.style.cursor = "pointer";
        });
    }, 0);
});

onBeforeUnmount(() => {
    document.querySelectorAll(".info-item__container:not(.statistics-container)").forEach(el => {
        el.removeEventListener("click", goToRatingPage);
    });
});

const goToRatingPage = (event) => {
    const container = event.currentTarget;
    const sectionKey = container.querySelector(".section-name")?.textContent.trim();

    let sectionParam = "user";

    if (sectionKey.includes("Личный")) {
        sectionParam = "user";
    } else if (sectionKey.includes("Командный")) {
        sectionParam = "team";
    } else if (sectionKey.includes("Университетский")) {
        sectionParam = "university";
    }

    router.push({ path: "/dashboard/rating", query: { section: sectionParam } });
};
</script>

<style scoped>
.dashboard-item {
    min-height: 475px;
}

.block-label {
    display: flex;
    align-items: center;
}

.block-subtitle {
    font-size: 16px;
    font-weight: 400;
    margin-left: 10px;
}

.info-item__container {
    min-height: 155px;
    max-height: 155px;
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

.highlight-user {
    font-weight: 500;
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
