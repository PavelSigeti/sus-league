<template>
    <div class="content-block">
        <p>Вы не состоите в команде :(</p>
        <Form  @submit="submit" :validation-schema="validationSchema">
            <div class="form-control">
                <label for="name">Создайте свою команду</label>
                <Field name="name" v-slot="{ field, errors }">
                    <input v-bind="field" type="text" :class="['form-input', {'invalid': !!errors.length} ]"
                           placeholder="Название команды"/>
                </Field>
                <ErrorMessage class="alert" name="name"/>
            </div>
            <button class="btn btn-default btn-full-width">Создать команду</button>
        </Form>
    </div>
</template>

<script>
import axios from "axios";
import {ref} from "vue";
import * as yup from "yup";
import {Field, Form, ErrorMessage} from 'vee-validate';
import {useStore} from "vuex";

export default {
    name: "AppCreateTeam",
    components: {
        Field, Form, ErrorMessage
    },
    emits: ['loading', 'loadData'],
    setup(_, {emit}) {
        const store = useStore();

        const validationSchema = yup.object({
            name: yup.string()
                .required('Введите название')
                .min(3, 'Название от 3-х символов')
                .max(64, 'Название до 64-х символов'),
        });

        const submit = async (values) => {
            emit('loading', true);
            try {
                await axios.post('/api/team/store', {
                    name: values.name,
                });
                emit('loadData');
            } catch (e) {
                if(e.response.status === 422) {
                    store.dispatch('notification/displayMessage', {
                        value: 'Такое имя команды уже существует',
                        type: 'error',
                    });
                } else {
                    console.log(e.message);
                }
            }
            emit('loading', false);
        };

        return {
            submit, validationSchema,
        }
    }
}
</script>

<style scoped>

</style>
