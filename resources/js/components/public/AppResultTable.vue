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
                            <th>Яхтсмен</th>
                            <th v-for="i in Object.keys(group[0]).length - 1">
                                #{{i}}
                            </th>
                            <th>Итог</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(user, idx) in group">
                            <td>{{idx+1}}</td>
                            <td>
                                <div class="result-table__name">
                                    {{user[0].name}} {{user[0].surname}} <span class="result-table__nick">{{user[0].nickname}}</span>
                                </div>
                            </td>
                            <td v-for="race in user">
                                {{
                                    printValue(race, user.sum, group.length)
                                }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
import {onMounted, ref} from 'vue';

export default {
    name: "AppResultTable",
    props: ['id'],
    setup(props,) {
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
            if(race.drop) {
                if( race.place === group + 1 ) {
                    return `(dnf, ${group + 1})`;
                } else {
                    return `(${race.place})`;
                }
            } else {
                return race.place ?? sum;
            }
        };

        return {
            results, statusTitle, printValue,
        }
    }
}
</script>

<style scoped>

</style>
