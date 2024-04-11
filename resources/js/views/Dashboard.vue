<template>
    <AppHeader>Личный кабинет</AppHeader>
    <main>
        <div class="container-fluid g-0">
            <div class="row">
                <div class="col-12" v-if="!greeting">
                    <div class="dashboard-item greeting">
                        Привет! Рады видеть тебя на нашем сайте :)
                        Вся система находится на стадии разработки и тестирования, и нам очень важно знать, что на данном этапе все работает как надо.
                        Если ты найдешь ошибки или будут предложения — смело пиши по кнопке в правом-верхнем углу.

                        Любые вопросы можешь задавать <a target="_blank" href="https://t.me/chirkovka">сюда</a>.
                        <div class="btn btn-default btn-settings"  @click="upd">Понятно</div>
                    </div>
                </div>
<!--                <div class="col-lg-6 col-xl-5 col-xxl-4">-->
<!--                    <TheTeamSettings />-->
<!--                </div>-->
                <div class="col-lg-12">
                    <div class="dashboard-item">
                        <h3>Актуальные гонки</h3>
                        <div class="dash-stage__container">
                            <AppDashActualStages></AppDashActualStages>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script setup>
import TheTeamSettings from "../components/user/team/TheTeamSettings.vue";
import AppDashActualStages from "../components/user/dash/AppDashActualStages.vue";
import AppHeader from "@/components/ui/AppHeader.vue";
import {ref} from "vue";

const greeting = ref( localStorage.getItem('greeting') ? true : false );

const upd = () => {
    localStorage.setItem('greeting', true);
    greeting.value = true;
};
</script>

<style lang="scss">
    .greeting {
        .btn {
            margin-top: 15px;
            padding: 10px 20px;
        }
    }
</style>
