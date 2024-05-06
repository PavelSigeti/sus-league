<template>
<div class="team-list" v-if="teams.length > 0 && !props.status">
    <div class="team-list__header">Выберите команду</div>
    <div
        :class="['team-list__item', {'team-list__item-select': team.id===select} ]"
        v-for="team in teams"
        :key="team.id"
        @click="select=team.id"
    >
        {{team.name}}
    </div>
    <div class="btn btn-default" v-if="select !== null">Зарегистрировать</div>
</div>
<div class="team-notification" v-if="teams.length === 0 && !props.status">
    Вы не состоите в команде или не являетесь капитаном
</div>
<div class="btn btn-disable btn-settings-280" v-if="props.status">Одна из ваших команд зарегестирована</div>
</template>

<script setup>
import {onMounted, ref} from "vue";

const props = defineProps(['status']);
const teams = ref([]);
const select = ref(null);

onMounted(async () => {
   const response = await axios.get('/api/team/capitan');
   teams.value = response.data.teams;
});

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
    background: #bde8ff;
    width: fit-content;
    padding: 5px 10px;
    border-radius: 5px;
    font-weight: 500;
}
</style>
