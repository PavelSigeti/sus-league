<template>
    <div class="dashboard-item" v-if="data">
        <AppLoader v-if="loading" />
        <h3>Личный зачет</h3>
        <div class="rating-item" v-for="(item, idx) in data.data" :key="item.user_id">
            <div class="rating-position">#{{idx+1}}</div>
            <div class="rating-title">
                <div class="rating-name"><div class="rating-nickname">{{ item.nickname }}</div>&nbsp;{{item.name}} {{item.surname}}</div>

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

<script>
import {onMounted, ref} from "vue";
import AppLoader from "../ui/AppLoader.vue";

export default {
    name: "AppUsersRating",
    components: {
        AppLoader
    },
    setup() {
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

        return {
            data, load, loading,
        }
    },
}
</script>

<style scoped>

</style>
