<template>
    <header>
        <div class="container">
            <div class="row aic row-homepage">
                <div class="col-6">
                    <router-link to="/">
                        <img src="@/static/logo.svg" alt="vrisc logo" class="logo">
                    </router-link>
                </div>
                <div class="col-6 jcfe">
                    <router-link to="/" class="btn btn-default register-btn" >На главную</router-link>
                </div>
            </div>
        </div>
    </header>
    <main>
        <div class="container">
            <div class="row jcc">
                <div class="col-12 col-md-6 jcc">
                    <Form class="modal-container mt30"
                          :validation-schema="validationSchema"
                          @submit="resetPassword"
                          :initial-values="formValues"
                    >
                        <AppLoader v-if="loading" />
                        <h2>Восстановление пароля</h2>
                        <div class="form-control">
                            <Field name="email" v-slot="{ field, errors }">
                                <input
                                    v-bind="field"
                                    type="email"
                                    :class="['form-input', {'invalid': !!errors.length} ]"
                                    placeholder="E-mail"
                                />
                            </Field>
                            <ErrorMessage class="alert" name="email" />
                        </div>
                        <div class="form-control">
                            <Field name="password" v-slot="{ field, errors }">
                                <input
                                    v-bind="field"
                                    type="password"
                                    :class="['form-input', {'invalid': !!errors.length} ]"
                                    placeholder="Пароль"
                                />
                            </Field>
                            <ErrorMessage class="alert" name="password" />
                        </div>
                        <div class="form-control">
                            <Field name="password_confirmation" v-slot="{ field, errors }">
                                <input
                                    v-bind="field"
                                    type="password"
                                    :class="['form-input', {'invalid': !!errors.length} ]"
                                    placeholder="Пароль еще раз"
                                />
                            </Field>
                            <ErrorMessage class="alert" name="password_confirmation" />
                        </div>
                        <button class="btn btn-default btn-home">Отправить</button>
                    </Form>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
import { ref } from 'vue';
import AppLoader from "../components/ui/AppLoader.vue";
import { useRoute, useRouter } from 'vue-router';
import axios from "axios";
import * as yup from "yup";
import {Field, Form, ErrorMessage} from 'vee-validate';
export default {
    name: "ResetPassword",
    components: {
        Field, Form, ErrorMessage,
        AppLoader,
    },
    setup() {
        const rote = useRoute();
        const router = useRouter();
        const loading = ref(false);
        const formValues = {
            email: rote.query.email,
            token: rote.params.token,
        };
        const validationSchema = yup.object({
            email: yup.string().required('Введите E-mail').email('Не корректный E-mail'),
            password: yup.string().required('Введите пароль').min(8, 'Пароль от 8 символов'),
            password_confirmation: yup
                .string()
                .required('Введите пароль')
                .oneOf([yup.ref('password')], 'Пароли должны совподать'),
        });
        const resetPassword = async (values) => {
            try {
                await axios.get('/sanctum/csrf-cookie');
                await axios.post('/reset-password', {
                    token: rote.params.token,
                    ...values,
                });
                router.push('/');
            } catch (e) {
                console.log(e.message);
            }
        }
        return {
            resetPassword, validationSchema, formValues,
            loading,
        }
    }
}
</script>

<style scoped>
</style>
