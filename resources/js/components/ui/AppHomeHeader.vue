<template>
    <header>
        <div class="container">
            <div class="row aic">
                <div class="col-6">
                    <router-link to="/"><img src="@/static/logo.svg" alt="vrisc logo" class="logo"></router-link>
                </div>
                <div class="col-6 jcfe">
                    <div class="login-btn btn btn-border" @click="login = true">Вход</div>
                    <div class="register-btn btn btn-default" @click="register = true">Регистрация</div>
                </div>
            </div>
        </div>
        <AppLoginForm v-if="login" @close="login = false" @switchReg="changeModal" @forget="toggleForget()"/>
        <keep-alive>
            <AppForgetPassword v-if="forget" @close="forget = false" @change="toggleForget()"/>
        </keep-alive>
        <keep-alive>
            <AppRegisterForm v-if="register" @close="register = false" @switchReg="changeModal" />
        </keep-alive>
    </header>
</template>

<script>
import AppLoginForm from "@/components/public/AppLoginForm.vue";
import AppRegisterForm from "@/components/public/AppRegisterForm.vue";
import AppForgetPassword from "@/components/public/AppForgetPassword.vue";
import {ref} from "vue";

export default {
    name: "AppHomeHeader",
    components: {
        AppLoginForm, AppRegisterForm, AppForgetPassword,
    },
    setup() {
        const login = ref(false);
        const register = ref(false);
        const forget = ref(false);

        const changeModal = () => {
            login.value = !login.value;
            register.value = !register.value;
        };
        const toggleForget = () => {
            login.value = !login.value;
            forget.value = !forget.value;
        };

        return {
            login, register, changeModal,
            forget, toggleForget,
        }
    }
}
</script>

<style scoped>

</style>
