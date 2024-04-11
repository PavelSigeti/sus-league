<template>
    <AppHeader>Серии</AppHeader>
    <main>
        <div class="container-fluid g-0">
            <div class="row">
                <div class="col-12">
                    <div class="dashboard-item">
                        <AppLoader v-if="loading" />
                        <h3>Добавить серию</h3>
                        <form @submit.prevent="submit">
                            <div class="form-control">
                                <label for="title">Название серии</label>
                                <input class="form-input" type="text" id="title" v-model="title" placeholder="Название серии">
                            </div>
                            <div class="form-control">
                                <label for="yacht">Тип лодки</label>
                                <input class="form-input" type="text" id="yacht" v-model="yacht" placeholder="Тип лодки">
                            </div>
                            <div class="form-control">
                                <label for="description">Описание</label>
                                <AppEditor v-model:modelValue="description" id="description" ref="editor" />
                            </div>
                            <button class="btn btn-default btn-settings">Создать</button>
                        </form>
                    </div>
                </div>
                <div class="col-12">
                    <h2 class="mb30">Список серий</h2>
                </div>
                <div
                    class="col-lg-3 col-md-6"
                    v-for="tournament in tournaments"
                    :key="tournament.id"
                >
                    <router-link
                        :to="{name: 'tournament.edit', params: {id: tournament.id}}"
                        class="stage-item"
                    >
                        <div class="stage-title">
                            {{tournament.title}}
                        </div>
                        <div class="stage-yacht">
                            {{tournament.yacht}}
                        </div>
                        <div class="btn btn-default btn-full-width">Подробнее</div>
                    </router-link>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
import {onMounted, ref} from 'vue';
import axios from "axios";
import { useStore } from 'vuex';
import AppEditor from "@/components/admin/AppEditor.vue";
import AppLoader from "@/components/ui/AppLoader.vue";
import AppHeader from "@/components/ui/AppHeader.vue";

export default {
    name: "tournament.index",
    components: {
        AppEditor, AppLoader, AppHeader,
    },
    setup() {
        const title = ref('');
        const yacht = ref('');
        const description = ref('');

        const tournaments = ref();

        const store = useStore();
        const loading = ref(false);
        const editor = ref(null);

        onMounted( async() => {
            try {
                const data = await axios.get('/api/admin/tournament');
                tournaments.value = data.data;
            } catch (e) {
                console.log(e.message);
            }
        });

        const submit = async () => {
            loading.value = true;
            try {
                const data = await axios.post('/api/admin/tournament/store', {
                    title: title.value,
                    yacht: yacht.value,
                    description: description.value,
                });
                store.dispatch('notification/displayMessage', {
                    value: 'Серия успешно создана',
                    type: 'primary',
                });
                const id = data.data.id;
                tournaments.value.unshift({
                    id,
                    title: title.value,
                    yacht: yacht.value,
                });
                title.value = '';
                yacht.value = '';
                editor.value.clear();
                loading.value = false;
            } catch (e) {
                store.dispatch('notification/displayMessage', {
                    value: 'Ошибка создания серии',
                    type: 'error',
                });
                loading.value = false;
            }
        };

        return {
            submit, title, yacht,
            description, loading, tournaments,
            editor,
        }
    }
}
</script>

<style scoped>

</style>
