<template>
    <div class="race-table__item race-table__total race-table__final" v-for="(value, key) in result" :key="key">
        <input type="number" v-model="result[key]" :placeholder="`Баллов в зачет`">
    </div>
    <div class="race-table__item">
        <button>Записать в БД</button>
    </div>

</template>

<script setup>
import {onMounted, ref} from "vue";

const props = defineProps(['id']);

const result = ref([]);


onMounted(async () => {
    const ans = await axios.get(`/api/admin/stage/${props.id}/results`);
    result.value = ans.data;
})
</script>

<style scoped>
.race-table__final {
    width: 120px;
    input {
        width: 100px;
    }
}
</style>
