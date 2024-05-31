<template>
    <AppHeader>Документы</AppHeader>
    <main>
        <div class="container-fluid g-0">
            <div class="row">
                <div class="col-12 mb30 df">
                    <router-link class="btn btn-default btn-settings" :to="{name: 'admin.docs-confirm'}">Смотреть проверенные</router-link>
                    <router-link class="btn btn-default btn-settings" :to="{name: 'admin.docs-cancel'}">Смотреть отмененные</router-link>
                </div>
                <div class="col-12">
                    <h2>Файлы на модерации</h2>
                    <div class="file-table">
                        <div class="file-table__head file-item">
                            <div class="file-item__1">ID</div>
                            <div class="file-item__2">Файл</div>
                            <div class="file-item__3">Пользователь</div>
                            <div class="file-item__4">Подтверждение</div>
                            <div class="file-item__4">Отказ</div>
                        </div>
                        <div class="file-item" v-for="file in files" :key="file.id">
                            <div class="file-item__1">
                                <div class="file-id">{{file.id}}</div>
                            </div>
                            <div class="file-item__2">
                                <a :href="'/storage/' + file.path" target="_blank">
                                    <i class="ri-file-pdf-2-line" v-if="file.path.includes('pdf')"></i>
                                    <img :src="'/storage/' + file.path" alt="img" v-else>
                                </a>
                                {{file.type}}
                            </div>
                            <div class="file-item__3">
                                <div class="file-user">
                                    <router-link target="_blank" :to="`/admin/user/${file.user.id}`" class="file-user__top">{{file.user.surname}} {{file.user.name}}</router-link>
                                    <div class="file-user__bottom">{{file.user.email}}</div>
                                </div>
                            </div>
                            <div class="file-item__4"><div class="btn btn-default btn-settings" @click="approve(file.id)">Подтвердить</div></div>
                            <div class="file-item__4"><div class="btn btn-default btn-settings" @click="cancel(file.id)">Отказать</div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script setup>
import AppHeader from "@/components/ui/AppHeader.vue";
import {onMounted, ref} from "vue";
import {useStore} from "vuex";

const store = useStore();
const files = ref([]);

onMounted(async () => {
    const response = await axios.get('/api/admin/docs/edit');
    files.value = response.data.files;
});

const approve = async (id) => {
    try {
        const ans = await axios.post(`/api/admin/docs/${id}/approve`);
        if(ans.data.result === true) {
            const idx = files.value.findIndex(e=>e.id === id);
            files.value.splice(idx, 1);
        }
    } catch (e) {
        console.log(e.message);
        store.dispatch('notification/displayMessage', {
            value: 'Ошибка при подтверждении файла',
            type: 'error',
        });
    }
};

const cancel = async (id) => {
    try {
        const ans = await axios.post(`/api/admin/docs/${id}/cancel`);
        if(ans.data.result === true) {
            const idx = files.value.findIndex(e=>e.id === id);
            files.value.splice(idx, 1);
        }
    } catch (e) {
        console.log(e.message);
        store.dispatch('notification/displayMessage', {
            value: 'Ошибка при подтверждении файла',
            type: 'error',
        });
    }
};
</script>

<style scoped>

.df {
    gap: 10px;
}
.file-user__top {
    font-size: 1em;
    height: fit-content;
    justify-content: flex-start;
    width: fit-content;
}
</style>
