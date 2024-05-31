<template>
    <AppHeader>Пользователи</AppHeader>
    <main>
        <div class="container-fluid g-0">
            <div class="row">
                <div class="col-12">
                    <div class="user-item" v-for="user in users" :key="user.id">
                        <router-link :to="`/admin/user/${user.id}`">#{{user.id}} {{user.surname}} {{user.name}} {{user.patronymic}}</router-link>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script setup>
import AppHeader from "@/components/ui/AppHeader.vue";
import {onMounted, ref} from "vue";

const users = ref([]);

onMounted(async () => {
   const ans = await axios.get('/api/admin/users');
   users.value = ans.data;
});

</script>

<style scoped>

</style>
