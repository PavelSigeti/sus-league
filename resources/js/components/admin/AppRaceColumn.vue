<template>
    <div class='race-table__item race-table__result' v-for="(_, key) in result" :key="key" >
<!--        <input type="text" v-model="result[key]" :placeholder="`(dnf, ${userAmount})`">-->
        <input type="number" v-model="result[key]" :placeholder="`Приход`">
        <input type="text" v-model="notes[key]" :placeholder="`Метка`" class="race-table__tag">
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
const notes = ref([]);
const userAmount = ref(0);

onMounted(async () => {
    const response = await axios.get(`/api/admin/race/${props.raceId}`);
    result.value = response.data.places;
    notes.value = response.data.notes;
    userAmount.value = Object.keys(result.value).length + 1;
});


const submit = async () => {
    try {
        await axios.post(`/api/admin/race/${props.raceId}/results`, {
            result: result.value,
            notes: notes.value,
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
.race-table__item {
    display: flex;
    gap: 5px;
}
.race-table .race-table__tag {
    width: 50px;
}
</style>
