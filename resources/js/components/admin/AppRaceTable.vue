<template>
    <div class="race-table">
        <h2 v-if="$props.status">{{raceTitle[$props.status]}} #{{$props.groupId}}</h2>
        <button class="btn btn-default btn-settings mb15" @click="addRace">Добавить гонку</button>
        <div class="race-table__header">
            <div class="race-table__row">
                <div class="race-table__item race-table__name">Яхтсмен</div>
                <div class="race-table__item race-table__result" v-for="i in raceAmount" :key="i">#{{i}}</div>
                <div class="race-table__item race-table__total">Итог</div>
            </div>
        </div>
        <div class="race-table__body">
            <div class="race-table__column">
                <div class="race-table__item race-table__name" v-for="user in usersData" :key="user.user_id">
                    {{user.nickname}}
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
        </div>
    </div>
</template>

<script>
import {ref, onMounted, computed} from "vue";
import AppRaceColumn from "@/components/admin/AppRaceColumn.vue";
import axios from "axios";

export default {
    name: "AppRaceTable",
    props: ['stageId', 'status', 'groupId'],
    components: {
        AppRaceColumn,
    },
    setup(props) {
        const raceData = ref({});
        const usersData = ref({});
        const lastRaceId = ref();
        const totalData = ref();

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
                const users = await axios.get(`/api/admin/race/${lastRaceId.value}/users`);
                usersData.value = users.data;
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

        return {
            raceData, raceAmount, usersData,
            addRace, remove, totalData,
            getTotal, raceTitle
        }
    }
}
</script>

<style scoped>

</style>
