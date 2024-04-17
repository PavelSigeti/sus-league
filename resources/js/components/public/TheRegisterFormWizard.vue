<template>
    <form @submit="submit" class="home-form" autocomplete="off">
        <div class="reg-step" v-if="step === 0" @keyup.enter="submit">
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
            <div class="form-info mb15">Отправляя форму, вы соглашаетесь на обработку <a class="underline link" target="_blank" href="/page/privacy">персональных данных</a></div>
            <div class="btn btn-default btn-home" @click="submit">Продолжить (1 из 2)</div>
            <div class="form-info jcc">Есть аккаунт?&nbsp;<span class="underline link" @click="$emit('switchReg')">Войти</span></div>
        </div>
        <div class="reg-step" v-if="step===1">
            <div class="form-control">
                <Field name="surname" v-slot="{ field, errors }">
                    <input v-bind="field" type="text" :class="['form-input', {'invalid': !!errors.length} ]" placeholder="Фамилия"  />
                </Field>
                <ErrorMessage class="alert" name="surname" />
            </div>
            <div class="form-control">
                <Field name="name" v-slot="{ field, errors }">
                    <input v-bind="field" type="text" :class="['form-input', {'invalid': !!errors.length} ]" placeholder="Имя"  />
                </Field>
                <ErrorMessage class="alert" name="name" />
            </div>
            <div class="form-control">
                <Field name="patronymic" v-slot="{ field, errors }">
                    <input v-bind="field" type="text" :class="['form-input', {'invalid': !!errors.length} ]" placeholder="Отчество"  />
                </Field>
                <ErrorMessage class="alert" name="patronymic" />
            </div>
            <div class="form-control">
                <Field name="birth" v-slot="{ field, errors }">
                    <input v-bind="field" :type="dateInit ? 'date' : 'text'" @focus="dateInit=true" :class="['form-input', {'invalid': !!errors.length} ]" placeholder="Дата рождения"  />
                </Field>
                <ErrorMessage class="alert" name="birth" />
            </div>
            <div class="form-control">
                <Field name="university_id" v-slot="{ field }">
                    <v-select
                        :options="universities"
                        placeholder="Выберите университет"
                        v-bind="field">
                    </v-select>
                    <ErrorMessage class="alert" name="university_id" />
                </Field>
            </div>
            <div class="form-info mb15">Если вашего университета нет, то пропусте это поле</div>
            <div class="form-info mb15">Отправляя форму, вы соглашаетесь на обработку &nbsp <a class="underline link" target="_blank" href="/page/privacy">персональных данных</a></div>
            <button class="btn btn-default btn-home">Зарегистрироваться</button>
            <div class="form-info jcc"><span class="underline link" @click="step=0">Вернуться назад (2 из 2)</span></div>
        </div>
    </form>
</template>

<script>
import { useForm, Field, ErrorMessage } from 'vee-validate';
import { ref, computed, onMounted } from 'vue';
import useUniversities from "../../composables/universities.js";
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';
import { useStore } from 'vuex';

export default {
    name: "TheRegisterFormWizard",
    props: [
        'validationSchema',
    ],
    emits: [
      'submitForm', 'switchReg',
    ],
    components: {
        vSelect, Field, ErrorMessage,
    },
    setup(props, {emit}) {
        const store = useStore();
        const step = ref(0);
        const {universities, getUniversities} = useUniversities();
        const dateInit = ref(false);

        onMounted(async ()=>{
            getUniversities();
        });
        const currentSchema = computed(() => {
            return props.validationSchema[step.value];
        });

        const { values, handleSubmit } = useForm({
            validationSchema: currentSchema,
            keepValuesOnUnmount: true,
        });

        const checkEmail = async (values) => {
          try {
              const response = await axios.post('/api/email', {
                  email: values.email,
              });
              return response.data;
          } catch (e) {
              console.log(e.message);
          }
        };

        const submit = handleSubmit(async (values) => {
            if(step.value === 0) {
                if(await checkEmail(values) === 0) {
                    step.value++;
                    return;
                } else {
                    store.dispatch('notification/displayMessage', {
                        value: 'Email уже занят!',
                        type: 'error',
                    })
                    return;
                }

            }

            emit('submitForm', values);
        });

        return {
            submit, step, universities,
            dateInit,
        }
    }
}
</script>

<style scoped lang="scss">

</style>
