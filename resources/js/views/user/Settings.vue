<template>
    <AppHeader>Настройки аккаунта</AppHeader>
    <main>
        <div class="container-fluid g-0">
            <div class="row">
                <div class="col-12 col-lg-8 col-xl-6">
                    <div class="dashboard-item">
                        <AppLoader v-if="loading" />
                        <h3>Данные пользователя</h3>
                        <div class="user-account__data">
                            <p>ФИО: <span class="b500">{{user.surname}} {{user.name}} {{user.patronymic}}</span></p>
                            <p>E-mail: <span class="b500">{{user.email}}</span></p>
                            <p v-if="user.university">Университет: <span class="b500">{{user.university}}</span></p>
                        </div>
                        <form @submit.prevent="submit" v-if="!user.university">
                            <div class="form-control" >
                                <label for="university_id">Ваш университет</label>
                                <Field name="university_id" v-slot="{ field }">
                                    <v-select
                                        :options="universities"
                                        placeholder="Выберите университет"
                                        v-bind="field">
                                    </v-select>
                                    <ErrorMessage class="alert" name="university_id" />
                                </Field>
                            </div>
                            <button class="btn btn-default btn-full-width">Сохранить</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
import {onMounted, ref} from 'vue';
import {useStore} from 'vuex';
import { useForm, Field, ErrorMessage } from 'vee-validate';
import * as yup from "yup";
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';
import AppLoader from "@/components/ui/AppLoader.vue";
import AppHeader from "@/components/ui/AppHeader.vue";
import axios from "axios";

export default {
    name: "Settings",
    components: {
        AppHeader, AppLoader, vSelect,
        Field, ErrorMessage,
    },
    setup() {
        const store = useStore();
        const user = ref(store.getters['auth/user']);
        const loading = ref(false);

        const universities = ref();

        const schema = yup.object({
            university_id: yup.object().nullable(),
        });

        const { values, handleSubmit } = useForm({
            validationSchema: schema,
        });

        onMounted(async () => {
            loading.value = true;
            try {
                const response = await axios.get('/api/user-settings');
                user.value.email = response.data.email;
                user.value.university = response.data.university;

                const universitiesResponse = await axios.get('/api/universities');
                universities.value = universitiesResponse.data;

            } catch (e) {
                console.log(e.message);
            }
            loading.value = false;
        });

        const submit = handleSubmit(async (values) => {
            loading.value = true;
            values.university_id = values.university_id ? values.university_id.code : null;
            try {
                await axios.patch('/api/user/update', values);
                user.value.university = universities.value.find(e => e.code === values.university_id).label;
                store.commit('auth/setUser', user.value);
            } catch (e) {
                console.log(e.message);
            }
            loading.value = false;
        });

        return {
            loading, user, universities,
            submit,
        };
    }
}
</script>

<style scoped>

</style>
