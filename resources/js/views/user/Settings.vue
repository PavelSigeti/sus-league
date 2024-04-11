<template>
    <AppHeader>Настройки аккаунта</AppHeader>
    <main>
        <div class="container-fluid g-0">
            <div class="row">
                <div class="col-lg-6 col-xl-5 col-xxl-4">
                    <div class="dashboard-item">
                        <AppLoader v-if="loading" />
                        <h3>Данные пользователя</h3>
                        <div class="user-account__data">
                            <p>Имя: <span class="b500">{{user.name}}</span></p>
                            <p>Фамилия: <span class="b500">{{user.surname}}</span></p>
                            <p>E-mail: <span class="b500">{{user.email}}</span></p>
<!--                            <p v-if="user.university">Университет: <span class="b500">{{user.university}}</span></p>-->
                        </div>
                        <form @submit.prevent="submit">
                            <div class="form-control">
                                <label for="nickname">Никнейм в Virtual Regatta</label>
                                <Field v-model="nickname" name="nickname" v-slot="{ field, errors }">
                                    <input v-bind="field" type="text" :class="['form-input', {'invalid': !!errors.length} ]" placeholder="Никнейм в Virtual Regatta"  />
                                </Field>
                                <ErrorMessage class="alert" name="nickname" />
                            </div>
<!--                            <div class="form-control" v-if="!user.university">-->
<!--                                <label for="university_id">Ваш университет</label>-->
<!--                                <Field name="university_id" v-slot="{ field }">-->
<!--                                    <v-select-->
<!--                                        :options="universities"-->
<!--                                        placeholder="Выберите университет"-->
<!--                                        v-bind="field">-->
<!--                                    </v-select>-->
<!--                                    <ErrorMessage class="alert" name="university_id" />-->
<!--                                </Field>-->
<!--                            </div>-->
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
        const user = store.getters['auth/user'];
        const userClear = user;
        const loading = ref(false);

        const nickname = ref(user.nickname);
        const universities = ref();

        const schema = yup.object({
            nickname: yup.string().required('Введите никнейм').min(2, 'От 2-х символов').max(32, 'До 32-х символов'),
            // university_id: yup.object().nullable(),
        });

        const { values, handleSubmit } = useForm({
            validationSchema: schema,
        });

        onMounted(async () => {
            loading.value = true;
            try {
                const response = await axios.get('/api/user-settings');
                user.email = response.data.email;
                user.university = response.data.university;

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
                userClear.nickname = values.nickname;
                store.commit('auth/setUser', userClear);
            } catch (e) {
                console.log(e.message);
            }
            loading.value = false;
        });

        return {
            loading, user, nickname,
            universities, submit,
        };
    }
}
</script>

<style scoped>

</style>
