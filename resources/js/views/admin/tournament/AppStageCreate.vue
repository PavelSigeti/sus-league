<template>
    <form>
        <AppLoader v-if="loading" />
        <h3>Создание этапа</h3>
        <div class="form-control">
            <label for="register_start">Начало регистрации</label>
            <input class="form-input" type="datetime-local" id="register_start" v-model="register_start">
        </div>
        <div class="form-control">
            <label for="register_end">Конец регистрации</label>
            <input class="form-input" type="datetime-local" id="register_end" v-model="register_end">
        </div>
        <div class="form-control">
            <label for="race_start">Начало гонок</label>
            <input class="form-input" type="datetime-local" id="race_start" v-model="race_start">
        </div>
        <div class="form-control">
            <label for="title">Название</label>
            <input class="form-input" type="text" id="title" v-model="title" placeholder="Название">
        </div>
        <div class="form-control">
            <label for="title">Краткое описание</label>
            <AppEditor v-model:modelValue="excerpt" id="excerpt" ref="excerptInput" />
        </div>
        <div class="form-control">
            <label for="title">Описание</label>
            <AppEditor v-model:modelValue="description" id="description" ref="descriptionInput" />
        </div>

        <button class="btn btn-default btn-settings" @click.prevent="submit">Создать</button>
    </form>
</template>

<script>
import {ref} from 'vue';
import axios from "axios";
import {useRoute} from 'vue-router';
import {useStore} from 'vuex';
import AppLoader from "@/components/ui/AppLoader.vue";
import AppEditor from "@/components/admin/AppEditor.vue";

export default {
    name: "AppStageCreate",
    components: {
        AppLoader, AppEditor,
    },
    emits: ['create'],
    setup(_, {emit}) {
        const route = useRoute();
        const store = useStore();

        const id = route.params.id;

        const register_start = ref('');
        const register_end = ref('');
        const race_start = ref('');
        const title = ref('');
        const excerpt = ref('');
        const description = ref('');
        const loading = ref(false);
        const excerptInput = ref(null);
        const descriptionInput = ref(null);

        const submit = async () => {
            loading.value = true;
            try {
                const response = await axios.post('/api/admin/stage/store', {
                    tournament_id: id,
                    register_start: register_start.value,
                    register_end: register_end.value,
                    race_start: race_start.value,
                    title: title.value,
                    excerpt: excerpt.value,
                    description: description.value,
                });
                emit('create', response.data);
                store.dispatch('notification/displayMessage', {
                    value: 'Этап успешно создана',
                    type: 'primary',
                });
                register_start.value = register_end.value = race_start.value = title.value = '';
                excerptInput.value.clear();
                descriptionInput.value.clear();
                loading.value = false;
            } catch (e) {
                store.dispatch('notification/displayMessage', {
                    value: 'Ошибка при создании этапа',
                    type: 'error',
                });
                loading.value = false;
            }
        };

        return {
            register_start, register_end, race_start,
            title, excerpt, description,
            submit, loading, excerptInput,
            descriptionInput,
        }
    }
}
</script>

<style scoped>

</style>
