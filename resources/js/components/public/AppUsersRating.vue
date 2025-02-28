<template>
    <div class="dashboard-item" v-if="data">
        <AppLoader v-if="loading" />
        <h3>Личный зачет</h3>
        <router-link :to="`/dashboard/users/${item.user_id}`" class="rating-item" v-for="(item, idx) in data.data" :key="item.user_id">
            <div class="rating-position">#{{idx+1}}</div>
                <div  class="rating-name">
                    {{item.surname}} {{item.name}} {{item.patronymic}}
                </div>
            <div class="rating-score">{{item.total}}</div>
        </router-link>
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
const link = ref('/api/rating/users');
const data = ref(false);

onMounted(async () => {
   try {
       const response = await axios.get(link.value);
       data.value = response.data;
   } catch (e) {
       console.log(e.message);
   }
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

</style>
