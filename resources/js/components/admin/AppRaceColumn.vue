<template>
    <div class='race-table__item race-table__result' v-for="(_, key) in result" :key="key" >
<!--        <input type="text" v-model="result[key]" :placeholder="`(dnf, ${userAmount})`">-->
        <input type="text" v-model="result[key]" :placeholder="`${key}`">
    </div>
    <div class="race-table__item race-table__result">
        <button @click="submit">Сохранить</button>
    </div>


</template>

<script setup>
import {onMounted, ref} from 'vue';
import axios from 'axios';
import { useStore } from 'vuex';

const store = useStore();

const props = defineProps(['raceId']);
const emit = defineEmits(['update']);

const result = ref([]);
const userAmount = ref(0);

onMounted(async () => {
    const response = await axios.get(`/api/admin/race/${props.raceId}`);
    result.value = response.data;
    userAmount.value = Object.keys(result.value).length + 1;
});


const submit = async () => {
    try {
        await axios.post(`/api/admin/race/${props.raceId}/results`, {
            result: result.value,
        });
        store.dispatch('notification/displayMessage', {
            value: 'Данные гонки обновлены',
            type: 'primary',
        });
        emit('update');
    } catch (e) {
        store.dispatch('notification/displayMessage', {
            value: 'Ошибка при обновлении данных гонки',
            type: 'error',
        });
    }
};
</script>

<style scoped>

</style>
