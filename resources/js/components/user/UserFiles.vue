<template>
<div class="col-12">
    <div class="dashboard-item">
        <h3>Документы</h3>
        <div class="file-container" v-if="files">
            <UserSpecificFile
                v-for="document in documentOptions"
                :document="document"
                :file="files.find(e=>e.type === document.type)"
            />
        </div>
    </div>
</div>
</template>

<script setup>
import {onMounted, ref} from "vue";
import UserSpecificFile from "@/components/user/UserSpecificFile.vue";

const documentOptions = ref([
    {'type': 'studak', 'title': 'Студенческий/Диплом'},
    {'type': 'passport', 'title': 'Паспорт'},
    {'type': 'insurance', 'title': 'Страховка'},
    {'type': 'license', 'title': 'Права на яхту'},
    {'type': 'antidoping', 'title': 'Сертефикат антидопинга'},
    {'type': '1444', 'title': 'Справака 1444н'},
    {'type': 'oms', 'title': 'ОМС'},
    {'type': 'category', 'title': 'Разряд'},
    {'type': 'vfps', 'title': 'Челенство ВФПС'},
    {'type': 'permit', 'title': 'Согласие на обработку данных'},
]);
const files = ref(null);

onMounted(async () => {
    const response = await axios.get('/api/user/docs');
    files.value = response.data.files;
});

</script>

<style scoped>
.file-container {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
}
</style>
