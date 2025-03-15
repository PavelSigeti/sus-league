<template>
    <div class="dashboard-item">
        <h3 class="block-label">Данные пользователя</h3>
        <template v-if="isLoading">
            <AppLoader :isSectionLoader="true"/>
        </template>
        <div v-if="!isLoading" class="info-item__container">
            <div 
                v-for="field in userFields" 
                :key="field.key" 
                class="info-item__row"
            >
                <span class="info-item__label">{{ field.title }}</span>
                <span class="info-item__dots"></span>
                <span class="info-item__value">{{ user?.[field.key] ?? '—' }}</span>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRoute } from "vue-router";
import AppLoader from "../ui/AppLoader.vue";
import axios from "axios";

const route = useRoute();
const user = ref({});
const isLoading = ref(true);

const userFields = ref([
    { key: "name", title: "Имя" },
    { key: "surname", title: "Фамилия" },
    { key: "patronymic", title: "Отчество" },
    { key: "age", title: "Возраст" },
    { key: "university", title: "Университет" },
    { key: "registration_date", title: "Дата регистрации" },
]);

onMounted(async () => {
    try {
        const response = await axios.get(`/api/user/${route.params.id}`);
        user.value = response.data;
        isLoading.value = false;
    } catch (error) {
        console.error("Ошибка при загрузке данных:", error);
        isLoading.value = false;
    }
});
</script>

<style scoped>
.dashboard-item {
    min-height: 150px;
}
</style>
