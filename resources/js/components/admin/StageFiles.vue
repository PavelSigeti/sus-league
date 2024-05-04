<template>
    <div class="dashboard-item">
        <h3>Загруженные файлы</h3>
        <div class="docs-container">
            <div class="docs-item" v-for="doc in docs" :key="doc.id">
                <div class="doc-delete" @click="destroy(doc.id)">Удалить</div>
                <a :href="'/storage/' + doc.path" class="docs-img" target="_blank">
                    <i class="ri-file-pdf-2-line" v-if="doc.path.includes('pdf')"></i>
                    <img :src="'/storage/' + doc.path" alt="doc-img" v-else>
                </a>
                <div class="docs-title">
                    {{ doc.title }}
                </div>
            </div>
        </div>
    </div>
    <div class="dashboard-item">
        <h3>Загрузить файлы</h3>
        <form @submit.prevent="submit">
            <div class="form-control">
                <label for="file">Файл</label>
                <input type="file" id="file" name="file" ref="fileInput">
            </div>
            <div class="form-control">
                <label for="title">Название</label>
                <input class="form-input" type="text" name="title" v-model="fileTitle">
            </div>
            <button class="btn btn-default btn-settings">Добавить</button>
        </form>
    </div>
</template>

<script setup>
import {onMounted, ref} from "vue";

const props = defineProps(['id']);
const docs = ref([]);
const fileInput = ref();
const fileTitle = ref();

onMounted(async () => {
    const response = await axios.get(`/api/admin/stage/${props.id}/files`);
    if(response.data.result){
        docs.value = [...response.data.files];
    }
});

const submit = async () => {
    const formData = new FormData();
    formData.append('file', fileInput.value.files[0]);
    formData.append('title', fileTitle.value);
    formData.append('id', props.id);

    if(!fileInput.value.files[0] || !fileTitle.value) {
        alert('Ошибка, оба поля должны быть заполнены!');
        return;
    }

    const ans = await axios.post('/api/admin/stage/file', formData, {
        headers: { "Content-Type": "multipart/form-data" },
    });

    if(ans.data.result === true) {
        docs.value.push(ans.data.file);
        fileInput.value = null;
        fileTitle.value = null;
    }
};

const destroy = async (id) => {
    const ans = await axios.delete(`/api/admin/stage/file/${id}/delete`);

    if(ans.data.result) {
        const idx = docs.value.findIndex(e=>e.id === id);

        docs.value.splice(idx, 1);
    }
};
</script>

<style scoped>
.docs-container {
    display: flex;
    gap: 10px;
}
.docs-item {
    height: 180px;
    width: 180px;
    background: #eee;
    border-radius: 10px;
    box-shadow: 0px 0px 5px 0px rgba(0, 0, 0, .25);
    position: relative;
}
.docs-img {
    width: 180px;
    height: 150px;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 10px;
    overflow: hidden;

    img {
        height: 150px;
        width: 180px;
        object-fit: cover;
    }
    i {
        font-size: 2em;
    }
}
.docs-title {
    padding: 5px;
    font-size: .9em;
}
.doc-delete {
    position: absolute;
    right: 5px;
    top: 5px;
    padding: 5px;
    background: #6c757d;
    border-radius: 5px;
    color: #fff;
    cursor: pointer;
    transition: .3s;
    &:hover {
        background: #333;
    }
}
</style>
