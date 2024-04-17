<template>
    <div class="content-block create-team">
        <Form  @submit="submit" :validation-schema="validationSchema">
            <div class="form-control">
                <label for="name">Создайте новую команду</label>
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

<script setup>
import {ref} from "vue";
import * as yup from "yup";
import {Field, Form, ErrorMessage} from 'vee-validate';
import {useStore} from "vuex";

const store = useStore();

const emit = defineEmits(['loading', 'loadData']);

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

</script>

<style scoped>
.create-team {
    padding: 15px;
    border-radius: 10px;
    margin-bottom: 0;
    margin-top: 15px;
    box-shadow: 0px 0px 30px 5px rgba(0, 0, 0, 0.1);
}
</style>
