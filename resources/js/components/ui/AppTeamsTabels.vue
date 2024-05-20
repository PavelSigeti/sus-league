<template>
    <table v-if="teams" class="result-table">
        <thead>
        <tr>
            <th>#</th>
            <th>Команда</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(team, i) in teams" :key="team.team_id">
            <td>{{i+1}}</td>
            <td>
                <div class="result-table__name">
                    <div class="result-table__first">{{team.team_name}}</div>
                    <div class="result-table__second">
                        <span v-for="user in team.users" :key="user.id">{{user.surname}} {{user.name}} {{user.patronymic}}</span>
                    </div>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
</template>

<script setup>
import {onMounted, ref} from "vue";

const teams = ref([]);
const props = defineProps(['id']);

onMounted(async () => {
    const ans = await axios.get(`/api/admin/team/users/${props.id}`);
    if(ans.data.result) {
        teams.value = ans.data.teams;
    }
})
</script>

<style scoped>

</style>
