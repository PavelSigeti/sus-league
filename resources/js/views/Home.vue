<template>
    <header>
        <div class="container">
            <div class="row aic">
                <div class="col-6">
                    <router-link class="header-link" to="/"><img src="@/static/logo.svg" alt="sus logo" class="logo"></router-link>
                </div>
                <div class="col-6 jcfe">
                    <div class="login-btn btn btn-border" @click="login = true">Вход</div>
                    <div class="register-btn btn btn-default" @click="register = true">Регистрация</div>
                </div>
            </div>
        </div>
    </header>
    <main>
        <AppLoginForm v-if="login" @close="login = false" @switchReg="changeModal" @forget="toggleForget()"/>
        <keep-alive>
            <AppForgetPassword v-if="forget" @close="forget = false" @change="toggleForget()"/>
        </keep-alive>
        <keep-alive>
            <AppRegisterForm v-if="register" @close="register = false" @switchReg="changeModal" />
        </keep-alive>
        <div class="container">
            <div class="row">
                <div class="col-12 df">
                    <div class="home-container">
                        <div class="home-main">
                            <h1>Добро пожаловать на <br>SUS LEAGUE: SEASON ONE</h1>
                            <div class="btn btn-default" @click="register = true">Принять участие</div>
                        </div>
                        <div class="home-links">
                            <router-link to="/stage" class="home-link"><div class="home-link__hover"></div><span>Регаты</span></router-link>
                            <router-link to="/rating" class="home-link"><div class="home-link__hover"></div><span>Рейтинг</span></router-link>
                            <router-link to="/page/reglament" class="home-link"><div class="home-link__hover"></div><span>Регламент</span></router-link>
                            <router-link to="/page/info" class="home-link"><div class="home-link__hover"></div><span>Инструкция</span></router-link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script setup>
import {ref} from 'vue';
import AppLoginForm from "../components/public/AppLoginForm.vue";
import AppRegisterForm from "../components/public/AppRegisterForm.vue";
import AppForgetPassword from "../components/public/AppForgetPassword.vue";
import AppHomeHeader from "../components/ui/AppHomeHeader.vue";


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
</script>

<style scoped>
.header-link {
    display: flex;
    justify-content: flex-start;
    align-items: center;
    height: 50px;
    width: fit-content;
}
</style>
