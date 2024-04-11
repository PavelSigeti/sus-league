<template>
  <div class="modal">
    <div class="modal-background" @click="$emit('close'); "></div>
    <div class="support-container">
        <div class="support">
            <div class="support-done" v-if="done">
                Ваше обращение отправлено!
            </div>
            <AppLoader v-if="loading"></AppLoader>
            <div class="close" @click="$emit('close');"></div>
            <h2>Обратная связь</h2>
            <Form @submit="submit" :validation-schema="validationSchema">
                <div class="form-control">
                    <Field name="title" v-slot="{ field, errors }">
                        <label for="title">Тема</label>
                        <input v-bind="field" id="title" type="text" :class="['form-input', {'invalid': !!errors.length} ]"
                               placeholder="Тема"/>
                    </Field>
                    <ErrorMessage class="alert" name="title"/>
                </div>
                <div class="form-control">
                    <Field name="text" v-slot="{ field, errors }">
                        <label for="text">Сообщение</label>
                        <textarea v-bind="field" id="text" :class="['form-textarea', {'invalid': !!errors.length} ]"
                                  placeholder="Сообщение"></textarea>
                    </Field>
                    <ErrorMessage class="alert" name="text"/>
                </div>
                <button class="btn btn-default btn-full-width">Отправить</button>
            </Form>
        </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import * as yup from "yup";
import {Field, Form, ErrorMessage} from 'vee-validate';
import AppLoader from "../ui/AppLoader.vue";
import {ref} from 'vue';

export default {
  name: "AppSupport",
  emits: [
    'close',
  ],
  components: {
    Field, Form, ErrorMessage,
    AppLoader,
  },
  setup() {
    const loading = ref(false);
    const done = ref(false);
    const validationSchema = yup.object({
      title: yup.string().required('Введите тему').min(5, 'От 5 символов').max(128, 'До 128 символов'),
      text: yup.string().required('Введите текст').min(20, 'От 20 символов').max(1024, 'До 1024 сиволов'),
    });

    const submit = async (valuse) => {
      loading.value = true;
      try {
        const response = await axios.post('/api/feedback', valuse);
      } catch (e) {
        console.log(e.message);
      }
      loading.value = false;
      done.value = true;
    };

    return {
      submit, loading, validationSchema,
      done,
    };
  }
}
</script>

<style scoped>

</style>
