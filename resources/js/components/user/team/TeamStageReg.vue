<template>
<div class="team-list" v-if="teams.length > 0 && !status">
    <div class="team-list__header">Выберите команду</div>
    <div
        :class="['team-list__item', {'team-list__item-select': team.id===select} ]"
        v-for="team in teams"
        :key="team.id"
        @click="select=team.id"
    >
        {{team.name}}
    </div>
    <div class="btn btn-default" v-if="select !== null" @click="reg">Зарегистрировать</div>
</div>
<div class="team-notification" v-if="teams.length === 0 && !status">
    Вы не состоите в команде или не являетесь капитаном
</div>
<div class="btn btn-disable btn-settings-280" v-if="status && teamReg">Команда "{{teamReg.name}}" зарегистрирована</div>
<div class="btn btn-default" v-if="status && teamReg.user_id === user.id" @click="cancel">Отказаться</div>
</template>

<script setup>
import {onMounted, ref} from "vue";
import {useStore} from "vuex";

const store = useStore();
const user = store.getters['auth/user'];
const props = defineProps(['status', 'stage']);
const emit = defineEmits(['reg', 'cancel']);
const status = ref(props.status);
const teams = ref([]);
const select = ref(null);
const teamReg = ref({});
const stage = ref({});

onMounted(async () => {
   const response = await axios.get('/api/team/capitan');
   teams.value = response.data.teams;

   if(props.status) {
       const teamResp = await axios.get(`/api/stage/${props.stage}/reg-info`);
       teamReg.value = teamResp.data.team;
   }

    // TODO: Отказаться скрыть
   // stage.value = await axios.get().data;
});

const reg = async () => {
    try {
        const ans = await axios.post(`/api/stage/accept`, {
            stage_id: props.stage,
            team_id: select.value,
        });

        if(ans.data.result) {
            emit('reg');
            teamReg.value = ans.data.team;
            status.value = true;
            store.dispatch('notification/displayMessage', {
                value: 'Команда зарегистрирована',
                type: 'primary',
            });
        } else {
            store.dispatch('notification/displayMessage', {
                value: ans.data.msg,
                type: 'error',
            });
        }
    } catch (e) {
        console.log(e.message);
        store.dispatch('notification/displayMessage', {
            value: e.message,
            type: 'error',
        });
    }
};

const cancel = async () => {
    try {
        const ans = await axios.post('/api/stage/cancel', {
            stage_id: props.stage,
            team_id: teamReg.value.id,
        });
        if(ans.data.result) {
            emit('cancel');
            select.value = null;
            teamReg.value = {};
            status.value = false;
            store.dispatch('notification/displayMessage', {
                value: 'Регистрация отменена',
                type: 'primary',
            });
        }
    } catch (e) {
        console.log(e.message);
    }
};

</script>

<style scoped>
.team-list {
    width: 320px;
    display: flex;
    justify-content: center;
    flex-direction: column;
    border: 2px solid #222;
    border-radius: 10px;
    align-items: center;
    margin-bottom: 15px;
    padding: 5px;
    box-sizing: border-box;
    gap: 10px;
    user-select: unset;
}
.team-list__header {
    font-weight: 500;
    margin-bottom: 5px;
}
.team-list__item {
    width: 305px;
    box-sizing: border-box;
    padding: 10px;
    background: #FDFDFD;
    box-shadow: 0px 0px 5px 1px rgba(0,0,0, .1);
    border-radius: 5px;
    cursor: pointer;
    border: 2px solid #FDFDFD;
    transition: .3s;
    &:hover {
        border: 2px solid #242424;
    }
}
.team-list__item-select {
    border: 2px solid #242424;
}
.btn-default {
    height: 36px;
}
.team-notification {
    background: #ddd;
    width: fit-content;
    padding: 5px 10px;
    border-radius: 5px;
    font-weight: 500;
}
</style>
