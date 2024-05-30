<template>
    <div class="result-tables">
        <div
            class="result-table__container"
            v-for="(status, key) in results" :key="key"
        >

            <div class="result-table__item" v-for="(group, _, idx)  in status" :key="_">
                <h3 v-if="statusTitle[key] !== 'Гонка'">{{statusTitle[key]}} #{{idx+1}}</h3>
                <table class="result-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Команда</th>
                            <th>Состав</th>
                            <th v-for="i in Object.keys(group[0]).length - 1">
                                #{{i}}
                            </th>
                            <th>Итог</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(team, idx) in group">
                            <td>{{idx+1}}</td>
                            <td>
                                <div class="result-table__name">
                                    {{team[0].name}}
                                </div>
                            </td>
                            <td>
                                <div class="result-table__name">
                                    <div class="result-table__second" v-if="teamUsers">
                                        <span v-for="user in teamUsers.find(e=>e.team_id === team[0].id).users" :key="user.id">{{user.surname}} {{user.name}} {{user.patronymic}}</span>
                                    </div>
                                </div>
                            </td>
                            <td v-for="race in team">
                                {{
                                    printValue(race, team.sum, group.length)
                                }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script setup>
import {onMounted, ref} from 'vue';

const props = defineProps(['id']);

const results = ref();
const statusTitle = {
    'default': 'Гонка',
    'group': 'Группа',
    'fleet': 'Флот',
};
const teamUsers = ref(null);

onMounted(async () => {
    try {
        const response = await axios.get(`/api/stage/${props.id}`);
        results.value = response.data;
    } catch (e) {
        console.log(e.message);
    }

    try {
        const ans = await axios.get(`/api/team/users/${props.id}`);
        if(ans.data.result) {
            teamUsers.value = ans.data.teams;
        }
    } catch (e) {
        console.log(e.message);
    }
});

const printValue = (race, sum, group) => {
    if(race.null) {
        return '—';
    }
    if(race.note) {
        return race.note;
    } else {
        return race.place ?? sum;
    }
};
</script>

<style scoped>
.result-table__item {
    margin-top: 30px;
}
.result-table__second {
    text-align: left;
}
</style>
