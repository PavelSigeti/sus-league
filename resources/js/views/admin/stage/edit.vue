<template>
    <AppHeader>{{ h1 }}</AppHeader>
    <main>
        <div class="container-fluid g-0">
            <div class="row">
                <div class="col-12">
                    <div class="dashboard-item">
                        <AppLoader v-if="loading" />
                        <form @submit.prevent="submit">
                            <div class="form-control">
                                <label for="register_start">Начало регистрации</label>
                                <input class="form-input" type="datetime-local" id="register_start" v-model="register_start">
                            </div>
                            <div class="form-control">
                                <label for="register_end">Конец регистрации</label>
                                <input class="form-input" type="datetime-local" id="register_end" v-model="register_end">
                            </div>
                            <div class="form-control">
                                <label for="race_start">Начало гонок</label>
                                <input class="form-input" type="datetime-local" id="race_start" v-model="race_start">
                            </div>
                            <div class="form-control">
                                <label for="title">Название Этапа</label>
                                <input class="form-input" type="text" id="title" v-model="title" placeholder="Название этапа">
                            </div>
                            <div class="form-control">
                                <label for="race_amount_drop">Кол-во выбросов (до 18 челов)</label>
                                <input class="form-input" type="text" id="race_amount_drop" v-model="race_amount_drop" placeholder="Кол-во выбросов (до 18 челов)">
                            </div>
                            <div class="form-control">
                                <label for="race_amount_drop">Кол-во выбросов в группах (от 19 челов)</label>
                                <input class="form-input" type="text" id="race_amount_group_drop" v-model="race_amount_group_drop" placeholder="Кол-во выбросов в группах (от 19 челов)">
                            </div>
                            <div class="form-control">
                                <label for="race_amount_drop">Кол-во выбросов во флотах (от 19 челов)</label>
                                <input class="form-input" type="text" id="race_amount_fleet_drop" v-model="race_amount_fleet_drop" placeholder="Кол-во выбросов во флотах (от 19 челов)">
                            </div>
                            <div class="form-control">
                                <label for="excerpt">Краткое описание</label>
                                <AppEditor v-if="excerpt" v-model:modelValue="excerpt" id="excerpt" />
                            </div>
                            <div class="form-control">
                                <label for="description">Описание</label>
                                <AppEditor v-if="description" v-model:modelValue="description" id="description" />
                            </div>
                            <div class="form-control">
                                <label for="participant_text">Для участников</label>
                                <AppEditor v-if="participant_text" v-model:modelValue="participant_text" id="participant_text" />
                            </div>

                            <button class="btn btn-default btn-settings">Редактировать</button>
                        </form>
                    </div>
                </div>
                <div class="col-12">
                    <div class="dashboard-item">
                        <AppUsersTables v-if="status === 'active' && users" :users="users"></AppUsersTables>

                        <div class="stage-table" v-if="status !== 'finished' && status !== 'active'" v-for="(groups, raceStatus, idx) in statusGroup" :key="idx">
                            <AppRaceTable v-for="groupId in groups"
                                          :stageId="id"
                                          :groupId="groupId"
                                          :status="raceStatus"
                                          :key="groupId"
                                          ref="child"
                            ></AppRaceTable>
                        </div>

                        <TheStageStatus v-if="status" :status="status" :id="id" @update="statusGroupFetch"/>

                        <AppResultTable v-if="status !== 'active'" :id="id" ref="resultComponent" />
                    </div>
                </div>
            </div>
        </div>
    </main>

</template>

<script>
import {onMounted, ref} from 'vue';
import axios from "axios";
import { useRoute } from 'vue-router';
import { useStore } from 'vuex';
import AppUsersTables from "@/components/ui/AppUsersTables.vue";
import AppRaceTable from "@/components/admin/AppRaceTable.vue";
import TheStageStatus from "@/components/admin/TheStageStatus.vue";
import AppResultTable from "@/components/public/AppResultTable.vue";
import AppLoader from "@/components/ui/AppLoader.vue";
import AppEditor from "@/components/admin/AppEditor.vue";
import AppHeader from "@/components/ui/AppHeader.vue";

export default {
    name: "stage.edit",
    components: {
        AppUsersTables, AppRaceTable, TheStageStatus,
        AppResultTable, AppLoader, AppEditor,
        AppHeader,
    },
    setup() {
        const loading = ref(false);
        const route = useRoute();
        const store = useStore();

        const id = route.params.id;
        const h1 = ref();
        const register_start = ref('');
        const register_end = ref('');
        const race_start = ref('');
        const title = ref('');
        const excerpt = ref('');
        const description = ref('');
        const participant_text = ref('');
        const users = ref();
        const status = ref();
        const race_amount_drop = ref();
        const race_amount_group_drop = ref();
        const race_amount_fleet_drop = ref();

        const child = ref(null);

        const statusGroup = ref();
        const resultComponent = ref();

        const statusGroupFetch = async (payload) => {
            try {
                const statusGroupData = await axios.get(`/api/admin/stage/${id}/meta`);
                statusGroup.value = statusGroupData.data;
                status.value = payload;
                if(resultComponent.value) {
                    resultComponent.value.update();
                }
            } catch (e) {
                console.log(e.message);
            }
        }

        onMounted( async() => {
            loading.value = true;
            try {
                const data = await axios.get(`/api/admin/stage/${id}/edit`);
                title.value = h1.value = data.data.title;
                register_start.value = data.data.register_start;
                register_end.value = data.data.register_end;
                race_start.value = data.data.race_start;
                excerpt.value = data.data.excerpt ? data.data.excerpt : ' ';
                description.value = data.data.description ? data.data.description : ' ';
                participant_text.value = data.data.participant_text ? data.data.participant_text : ' ';
                users.value = data.data.users;
                race_amount_drop.value = data.data.race_amount_drop;
                race_amount_group_drop.value = data.data.race_amount_group_drop;
                race_amount_fleet_drop.value = data.data.race_amount_fleet_drop;
                await statusGroupFetch(data.data.status);
                loading.value = false;

                console.log('data', data.data);
            } catch (e) {
                console.log(e.message);
                loading.value = false;
            }
        });

        const submit = async () => {
            loading.value = true;
            try {
                await axios.patch(`/api/admin/stage/${id}/update`, {
                    title: title.value,
                    description: description.value,
                    excerpt: excerpt.value,
                    participant_text: participant_text.value,
                    register_start: register_start.value,
                    register_end: register_end.value,
                    race_start: race_start.value,
                    race_amount_drop: race_amount_drop.value,
                    race_amount_group_drop: race_amount_group_drop.value,
                    race_amount_fleet_drop: race_amount_fleet_drop.value,
                });
                if(child.value) {
                    for (let comp of child.value) {
                        comp.getTotal();
                    }
                }
                store.dispatch('notification/displayMessage', {
                    value: 'Этап успешно обновлен',
                    type: 'primary',
                });
                loading.value = false;
            } catch (e) {
                console.log(e.message);
                store.dispatch('notification/displayMessage', {
                    value: 'Ошибка при обновлении этапа',
                    type: 'error',
                });
                loading.value = false;
            }
        };


        return {
            register_start, register_end, race_start,
            title, excerpt, description,
            submit, users, status,
            id, statusGroup, child,
            race_amount_drop, race_amount_group_drop, race_amount_fleet_drop,
            statusGroupFetch, resultComponent, h1,
            loading, participant_text,
        }
    }
}
</script>

<style scoped>

</style>
