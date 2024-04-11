<template>
    <AppHeader>{{ h1 }}</AppHeader>
    <main>
        <div class="container-fluid g-0">
            <div class="row">
                <div class="col-12">
                    <div class="dashboard-item">
                        <AppLoader v-if="loading" />
                        <form>
                            <div class="form-control">
                                <label for="title">Заголовок</label>
                                <input class="form-input" type="text" name="title" v-model="title" id="title" placeholder="Заголовок">
                            </div>
                            <div class="form-control">
                                <label for="slug">URI (Slug)</label>
                                <input class="form-input" type="text" name="slug" v-model="slug" id="slug" placeholder="URI (ЧПУ)">
                            </div>
                            <div class="form-control">
                                <label for="content">Содержание</label>
                                <AppEditor v-if="text" v-model:modelValue="text" id="content" />
                            </div>
                            <button class="btn btn-default btn-settings" @click.prevent="submit">Обновить</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
import {onMounted, ref} from 'vue';
import AppEditor from "@/components/admin/AppEditor.vue";
import AppHeader from "@/components/ui/AppHeader.vue";
import {useStore} from "vuex";
import {useRoute} from "vue-router";
import AppLoader from "@/components/ui/AppLoader.vue";

export default {
    name: "page.edit",
    components: {
        AppEditor, AppHeader, AppLoader,
    },
    setup() {
        const store = useStore();
        const route = useRoute();

        const loading = ref(false);

        const h1 = ref();
        const title = ref();
        const slug = ref();
        const text = ref('');
        const id = route.params.id;

        onMounted(async() => {
            loading.value = true;
            try {
                const response = await axios.get(`/api/admin/page/${id}`);
                const data = response.data;
                title.value = h1.value = data.title;
                slug.value = data.slug;
                text.value = data.text;
            } catch (e) {
                store.dispatch('notification/displayMessage', {
                    value: 'Ошибка при загрузке страницы',
                    type: 'error',
                });
            }
            loading.value = false;
        });

        const submit = async () => {
            try {
                await axios.patch(`/api/admin/page/${id}/update`, {
                    title: title.value,
                    slug: slug.value,
                    text: text.value,
                });
                store.dispatch('notification/displayMessage', {
                    value: 'Страница обновлена',
                    type: 'primary',
                });
            } catch (e) {
                store.dispatch('notification/displayMessage', {
                    value: 'Ошибка при создании страницы',
                    type: 'error',
                });
            }
        }

        return {
            submit, title, text,
            slug, h1, AppLoader,
            loading,
        }
    }
}
</script>

<style scoped>

</style>
