<template>
    <div class="race-table__item race-table__total race-table__final" v-for="(value, key) in data" :key="key">
        <input type="number" v-model="result[value.team_id]" :placeholder="`Очков в личный`">
    </div>
    <div class="race-table__item">
        <button @click="submit">Записать в БД</button>
    </div>

</template>

<script setup>
import {onMounted, ref} from "vue";

const props = defineProps(['id', 'groupId']);

const data = ref([]);
const result = ref({});


onMounted(async () => {
    const ans = await axios.get(`/api/admin/stage/${props.id}/results`);
    data.value = ans.data.filter(e => e.group_id === props.groupId);
});

const submit = async () => {
    const ans = await axios.post('/api/admin/stage/user/result', {
        stage_id:  props.id,
        results: result.value,
    });

    console.log(ans.data);
};
</script>

<style scoped>
.race-table__final {
    width: 120px;
    input {
        width: 100px;
    }
}
</style>
