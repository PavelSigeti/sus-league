<template>
    <AppHeader>Создать страницу</AppHeader>
    <main>
        <div class="container-fluid g-0">
            <div class="row">
                <div class="col-12">
                    <div class="dashboard-item">
                        <AppLoader v-if="loading" />
                        <form @submit.prevent>
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
                                <AppEditor v-model:modelValue="text" id="content" />
                            </div>
                            <button class="btn btn-default btn-settings" @click.prevent="submit">Создать</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

</template>

<script>
import {ref} from 'vue';
import AppEditor from "@/components/admin/AppEditor.vue";
import {useStore} from "vuex";
import {useRouter} from "vue-router";
import AppHeader from "@/components/ui/AppHeader.vue";
import AppLoader from "@/components/ui/AppLoader.vue";

export default {
    name: "page.create",
    components: {
        AppEditor, AppHeader, AppLoader,
    },
    setup() {
        const store = useStore();
        const router = useRouter();

        const loading = ref(false);

        const title = ref();
        const slug = ref();
        const text = ref('');

        const submit = async () => {
            loading.value = true;
            try {
                const response = await axios.post('/api/admin/page/store', {
                    title: title.value,
                    slug: slug.value,
                    text: text.value,
                });
                router.push(`/admin/page/${response.data}`);
            } catch (e) {
                store.dispatch('notification/displayMessage', {
                    value: 'Ошибка при создании страницы',
                    type: 'error',
                });
            }
            loading.value = false;
        }

        return {
            submit, title, text,
            slug, loading,
        }
    }
}
</script>

<style scoped>

</style>
