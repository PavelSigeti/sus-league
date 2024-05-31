<template>
    <AppHeader>Пользователь: {{user.surname}} {{user.name}} {{user.patronymic}}</AppHeader>
    <main>
        <div class="container-fluid g-0">
            <div class="row">
                <div class="col-12">
                    <div class="dashboard-item">
                        <div class="user-account__data">
                            <p>ФИО: <span class="b500">{{user.surname}} {{user.name}} {{user.patronymic}}</span></p>
                            <p>E-mail: <span class="b500">{{user.email}}</span></p>
                            <p v-if="user.university">Университет: <span class="b500">{{user.university}}</span></p>
                        </div>
                    </div>
                    <div class="dashboard-item">
                        <UserFilesAdmin />
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script setup>
import AppHeader from "@/components/ui/AppHeader.vue";
import {onMounted, ref} from "vue";
import {useRoute} from "vue-router";
import axios from "axios";
import UserFilesAdmin from "@/components/admin/UserFilesAdmin.vue";

const user = ref({});
const route = useRoute();

onMounted(async () => {
    try {
        const ans = await axios.get(`/api/admin/user/${route.params.id}`);
        user.value = ans.data;

        const universitiesResponse = await axios.get('/api/universities');
        user.value.university = universitiesResponse.data.find(e=>e.code === ans.data.university_id).label;
    } catch (e) {
        console.log(e.message);
    }
});

</script>

<style scoped>

</style>
