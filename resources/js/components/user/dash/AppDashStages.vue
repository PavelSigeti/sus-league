<template>
    <div class="dash-stage">
        <AppLoader v-if="loading" />
        <div class="dash-stage__header">
            <router-link :to="`/dashboard/stage/${stage.id}`" class="dash-stage__title">{{stage.title}}</router-link>
            <div class="dash-stage__tournament">{{stage.tournament}}</div>
            <div class="user-stage__participant" v-if="stage.users_exists">Вы участвуете</div>
        </div>
        <div class="dash-stage__date">
            <span>Начало регистрации: {{time(stage.register_start)}}</span>
            <span>Окончание регистрации: {{time(stage.register_end)}}</span>
        </div>
        <div class="btn btn-disable btn-settings-280" v-if="new Date(stage.register_start) > now">
            Регистрация не началась
        </div>

        <TeamStageReg
            :stage="stage.id"
            :status="stage.users_exists"
            v-if="teamReg || stage.users_exists"
            @reg="stage.users_exists = true"
            @cancel="teamReg=false; stage.users_exists = false"
        />
        <div
            v-if="stage.status === 'active' && new Date(stage.register_end) > now && !teamReg && !stage.users_exists"
            :class="['btn', 'btn-settings-280', 'btn-default']"
            @click="teamReg=true"
        >Принять участие</div>
        <div class="btn btn-disable btn-settings-280" v-if="new Date(stage.register_end) < now">
            Регистрация закончилась
        </div>
    </div>
</template>

<script setup>
import {onBeforeUnmount, onMounted, ref} from 'vue';
import AppLoader from "../../ui/AppLoader.vue";
import {time} from "@/utils/time.js";
import {useStore} from "vuex";
import TeamStageReg from "@/components/user/team/TeamStageReg.vue";

const props = defineProps(['stage']);

const getDate = () => {
    now.value = new Date();
}

const store = useStore();
const stage = ref(props.stage);

const loading = ref(false);
const teamReg = ref(false);

const now = ref();
onMounted(()=>{
    getDate();
});

const updateTimeInterval = setInterval(getDate, 10000);
onBeforeUnmount(() => {
    clearInterval(updateTimeInterval);
});
</script>

<style scoped>

</style>
