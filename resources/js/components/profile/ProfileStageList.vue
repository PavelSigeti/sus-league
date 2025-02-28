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

const fetchMockData = async () => {
    setTimeout(() => {
        // Замоканные данные
        stages.value = [
            {
                id: 1,
                title: "Первый этап MX700",
                tournament: "Турнир MX700",
                date: "07.11.2022",
                result: "5 место",
                participants: 36
            },
            {
                id: 2,
                title: "Второй этап MX700",
                tournament: "Турнир MX700",
                date: "14.11.2022",
                result: "2 место",
                participants: 40
            },
            {
                id: 1,
                title: "Первый этап MX700",
                tournament: "Турнир MX700",
                date: "07.11.2022",
                result: "5 место",
                participants: 36
            },
            {
                id: 2,
                title: "Второй этап MX700",
                tournament: "Турнир MX700",
                date: "14.11.2022",
                result: "2 место",
                participants: 40
            }
        ];
        isLoading.value = false;
    }, 1500);
};

onMounted(async () => {
    fetchMockData();
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
