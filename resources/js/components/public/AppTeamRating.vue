<template>
    <div class="dashboard-item" v-if="data">
        <AppLoader v-if="loading" />
        <div class="header-with-selector">
            <h3 class="title">Командный зачет</h3>
            <div class="year-selector">
                <select v-model="selectedYear" @change="fetchData">
                    <option value="">Все года</option>
                    <option v-for="year in availableYears" :value="year" :key="year">
                        {{ year }}
                    </option>
                </select>
            </div>
        </div>
        
        <div class="rating-item" v-for="(item, idx) in data.data" :key="item.user_id">
            <div class="rating-position">#{{idx+1}}</div>
            <div class="rating-title">
                <div class="rating-name">{{item.name}}</div>
            </div>
            <div class="rating-score">{{item.total}}</div>
        </div>
        
        <div class="jcc">
            <div class="btn btn-default btn-settings-280 mt10"
                 v-if="data.current_page < data.last_page"
                 @click="load"
            >Показать еще</div>
        </div>
    </div>
</template>

<script setup>
import {onMounted, ref} from "vue";
import AppLoader from "../ui/AppLoader.vue";

const loading = ref(false);
const currentYear = new Date().getFullYear();
const selectedYear = ref(currentYear.toString());
const availableYears = ref([2024, 2025]);
const data = ref(false);

const getLink = () => {
    return selectedYear.value
        ? `/api/rating/team/${selectedYear.value}`
        : '/api/rating/team';
};

const fetchData = async () => {
    loading.value = true;
    try {
        const response = await axios.get(getLink());
        data.value = response.data;
    } catch (e) {
        console.log(e.message);
    } finally {
        loading.value = false;
    }
};

onMounted(async () => {
    await fetchData();
});

const load = async () => {
    loading.value = true;
    try {
        const response = await axios.get(data.value.next_page_url);
        data.value.data.push(...response.data.data);
        data.value.next_page_url = response.data.next_page_url;
        data.value.current_page = response.data.current_page;
    } catch (e) {
        console.log(e.message);
    }
    loading.value = false;
};
</script>

<style scoped>
.title {
    margin-bottom: 0px;
}
</style>