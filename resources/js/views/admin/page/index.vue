<template>
    <AppHeader>Страницы</AppHeader>
    <main>
        <div class="container-fluid g-0">
            <div class="row">
                <div class="col-12 mb30">
                    <router-link class="btn btn-default btn-settings" :to="{name: 'admin.pages.create'}">Добавить страницу</router-link>
                </div>
                <div class="col-lg-6" v-for="item in pages" :key="item.id">
                    <router-link :to="{name: 'admin.pages.edit', params: {id: item.id}}" class="page-link">
                        <span>{{item.title}}</span>
                        <div class="btn btn-default btn-settings">Подробнее</div>
                    </router-link>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
import {onMounted, ref} from "vue";
import axios from "axios";
import AppHeader from "@/components/ui/AppHeader.vue";

export default {
    name: "index",
    components: {
        AppHeader,
    },
    setup() {

        const pages = ref();

        onMounted(async ()=>{
            try {
                const response = await axios.get('/api/admin/pages');
                pages.value = response.data;
            } catch (e) {
                console.log(e.message);
            }
        });

        return {
            pages,
        }
    },
}
</script>

<style scoped>

</style>
