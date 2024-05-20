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

onMounted(async () => {
    try {
        const response = await axios.get(`/api/stage/${props.id}`);
        results.value = response.data;
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
</style>
