<template>
    <div class="modal">
        <div class="modal-background" @click="$emit('close');"></div>
        <div class="modal-container">
            <div class="close" @click="$emit('close');"></div>
            <AppLoader v-if="loading"/>
            <div class="loading-overlay" v-if="msg">
                Проверьте свою почту
            </div>
            <h2>Восстановить пароль</h2>
            <Form @submit="reset" class="home-form" :validation-schema="validationSchema">
                <div class="form-control">
                    <Field name="email" v-slot="{ field, errors }">
                        <input v-bind="field" type="email" :class="['form-input', {'invalid': !!errors.length} ]"
                               placeholder="E-mail"/>
                    </Field>
                    <ErrorMessage class="alert" name="email"/>
                </div>

                <button class="btn btn-default btn-home">Отправить</button>
                <div class="form-info jcc">Вернуться обратно&nbsp;<span class="underline link" @click="$emit('change');">Войти</span>
                </div>
            </Form>
        </div>
    </div>
</template>

<script>
import {ref} from "vue";
import {useStore} from "vuex";
import * as yup from "yup";
import {Field, Form, ErrorMessage} from 'vee-validate';
import AppLoader from "../ui/AppLoader.vue";
import axios from "axios";

export default {
    name: "AppForgetPassword",
    components: {
        Field, Form, ErrorMessage,
        AppLoader,
    },
    emits: [
        'close', 'change',
    ],
    setup() {
        const store = useStore();

        const loading = ref(false);

        const validationSchema = yup.object({
            email: yup.string().required('Введите E-mail').email('Не корректный E-mail'),
        });

        const msg = ref(false);

        const reset = async (values) => {
            loading.value = true;
            try {
                await axios.get('/sanctum/csrf-cookie');
                await axios.post('/forgot-password', {
                    email: values.email,
                });
                msg.value = 'Проверьте свою почту!';
            } catch (e) {
                console.log(e.message);
            }
            loading.value = false;
            msg.value = true;
        };

        return {
            validationSchema, loading,
            reset, msg,
        }
    }
}
</script>

<style scoped>

</style>
