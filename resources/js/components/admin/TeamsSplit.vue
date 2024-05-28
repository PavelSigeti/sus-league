<template>
    <table v-if="teams.length > 0" class="result-table">
        <thead>
        <tr>
            <th>#</th>
            <th>Команды</th>
            <th>Группа</th>
            <th>Удаление</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(team, i) in teams" :key="i">
            <td>{{i+1}}</td>
            <td>
                <div class="result-table__name">
                    <div class="result-table__first">{{team.team_name}}</div>
                    <div class="result-table__second">
                        <span v-for="user in team.users" :key="user.id">{{user.surname}} {{user.name}} {{user.patronymic}}</span>
                    </div>
                </div>
            </td>
            <td>
                <input class="split-number-input" type="number" v-model="result[team.team_id]">
            </td>
            <td>
                <button class="btn btn-default btn-settings" @click="remove(team.team_id)">Удалить</button>
            </td>
        </tr>
        </tbody>
    </table>
    <button class="btn btn-default btn-settings mt15" @click="createGroup">Сформировать группы</button>
</template>

<script setup>
import {onMounted, ref} from "vue";
import {useStore} from "vuex";

const emit = defineEmits(['upd']);
const props = defineProps(['id']);
const teams = ref([]);
const result = ref({});
const store = useStore();

const createGroup = async () => {
    if(Object.keys(result.value).length !== teams.value.length) {
        store.dispatch('notification/displayMessage', {
            type: 'error',
            value: 'Распределите все команды или удалите лишние',
        });
        return;
    }
    const ans = await axios.post(`/api/admin/stage/${props.id}/group`, {
        groups: result.value,
    });

    if(ans.data.result) {
        emit('upd');
    } else {
        store.dispatch('notification/displayMessage', {
            type: 'error',
            value: 'Ошибка попробуйте позже',
        });
    }

};

const remove = async (teamId) => {
    const ans = await axios.post(`/api/admin/remove-team/${teamId}/stage/${props.id}`);
    if (ans.data.result) {
        store.dispatch('notification/displayMessage', {
            type: 'primary',
            value: 'Команда удалена'
        });
        await init();
    } else {
        store.dispatch('notification/displayMessage', {
            type: 'error',
            value: ans.data.msg,
        });
    }
};

const init = async () => {
    const ans = await axios.get(`/api/team/users/${props.id}`);
    if(ans.data.result) {
        teams.value = ans.data.teams;
    }
}

onMounted(async () => {
    await init();
});
</script>

<style scoped>
.split-number-input {
    width: 60px;
    height: 40px;
}
</style>
