<template>
    <div class="race-table">
        <h2 v-if="$props.status">{{raceTitle[$props.status]}} #{{$props.groupId}}</h2>
        <button class="btn btn-default btn-settings mb15" @click="addRace">Добавить гонку</button>
        <div class="race-table__header">
            <div class="race-table__row">
                <div class="race-table__item race-table__name">Команда</div>
                <div class="race-table__item race-table__result" v-for="i in raceAmount" :key="i">#{{i}}</div>
                <div class="race-table__item race-table__total">Итог</div>
                <div class="race-table__item race-table__total race-table__final">Командный зачет</div>
                <div class="race-table__item race-table__total race-table__final">Личный зачет</div>
            </div>
        </div>
        <div class="race-table__body">
            <div class="race-table__column">
                <div class="race-table__item race-table__name" v-for="team in teams" :key="team.team_id">
                    {{team.team_name}}
                </div>
            </div>
            <div class="race-table__column" v-for="(race, idx) in raceData" :key="race.race_id">
                <AppRaceColumn :raceId="race.race_id" @update="getTotal"/>
                <div class="race-table__item race-table__result" v-if="raceData.length > 1 && idx+1 !== 1">
                    <button @click="remove(race.race_id)">Удалить</button>
                </div>
            </div>
            <div class="race-table__column">
                <div class="race-table__item race-table__total" v-for="(total, key) in totalData" :key="key">
                    {{total}}
                </div>
            </div>
            <div class="race-table__column">
                <StageResultPoints :id="props.stageId" :groupId="props.groupId" />
            </div>
            <div class="race-table__column">
                <StageUsersResults :id="props.stageId" :groupId="props.groupId" />
            </div>
        </div>
    </div>
</template>

<script setup>
import {ref, onMounted, computed} from "vue";
import AppRaceColumn from "@/components/admin/AppRaceColumn.vue";
import StageResultPoints from "@/components/admin/StageResultPoints.vue";
import StageUsersResults from "@/components/admin/StageUsersResults.vue";

const props = defineProps(['stageId', 'status', 'groupId']);
const raceData = ref({});
const teams = ref({});
const lastRaceId = ref();
const totalData = ref();
const result = ref([]);

const raceTitle = ref({
    default: 'Гонка',
    group: 'Группа',
    fleet: 'Флот'
});

const raceAmount = computed( () => Object.keys(raceData.value).length ?? 0);

const getTotal = async () => {
    try {
        const total = await axios.get(`/api/admin/stage/${props.stageId}/${props.groupId}/${props.status}/total`);
        totalData.value = total.data;
    } catch (e) {
        console.log(e.message);
    }
};

onMounted(async () => {
    try {
        const races = await axios.get(
            `/api/admin/stage/${props.stageId}/races/${props.status}/group/${props.groupId}`
        );
        raceData.value = races.data;

        lastRaceId.value = raceData.value[0].race_id;
        const teamData = await axios.get(`/api/admin/race/${lastRaceId.value}/teams`);
        teams.value = teamData.data;
        await getTotal();
    } catch (e) {
        console.log(e.message);
    }

});

const addRace = async () => {
    try {
        const response = await axios.post('/api/admin/race/create', {
            stage_id: props.stageId,
            group_id: props.groupId,
            status: props.status,
            lastRaceId: lastRaceId.value,
        });
        const race = response.data;
        raceData.value.push({
            race_id: race.id,
            group_id: race.group_id,
            status: race.status,
        });

        await getTotal();
    } catch (e) {
        console.log(e.message);
    }
};

const remove = async (id) => {
    try {
        await axios.post(`/api/admin/race/${id}/remove`);
        raceData.value.splice(raceData.value.findIndex(item => item.race_id === id), 1);

        await getTotal();
    } catch (e) {
        console.log(e.message);
    }
};
</script>

<style scoped>
.race-table__final {
    width: 120px;
}
</style>
