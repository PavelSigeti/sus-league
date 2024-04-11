<template>
    <AppHeader>{{ h1 }}</AppHeader>
    <main>
        <div class="container-fluid g-0">
            <div class="row">
                <div class="col-12">
                    <div class="dashboard-item">
                        <AppLoader v-if="loading"/>
                        <form @submit.prevent="submit">
                            <div class="form-control">
                                <label for="title">Название серии</label>
                                <input class="form-input" type="text" id="title" v-model="title" placeholder="Название серии">
                            </div>
                            <div class="form-control">
                                <label for="yacht">Тип лодки</label>
                                <input class="form-input" type="text" id="yacht" v-model="yacht" placeholder="Тип лодки">
                            </div>
                            <div class="form-control">
                                <label for="description">Описание</label>
                                <AppEditor v-if="description" v-model:modelValue="description" id="description" />
                            </div>
                            <button class="btn btn-default btn-settings">Редактировать</button>
                        </form>
                    </div>
                </div>
                <div class="col-12">
                    <div class="dashboard-item">
                        <AppStageCreate @create="addStage"/>
                    </div>
                </div>
                <div
                    class="col-lg-3 col-md-6"
                    v-for="stage in stages" :key="stage.id"
                >
                    <router-link class="stage-item" :to="{name: 'stage.edit', params: {id:stage.id}}">
                        <div class="stage-title">
                            {{stage.title}}
                        </div>
                        <div class="stage-yacht">
                            {{yacht}}
                        </div>
                        <div class="stage-time">
                            {{time(stage.race_start)}}
                        </div>
                        <div class="btn btn-default btn-full-width">Подробнее</div>
                    </router-link>
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
import AppStageCreate from "./AppStageCreate.vue";
import AppLoader from "@/components/ui/AppLoader.vue";
import AppEditor from "@/components/admin/AppEditor.vue";
import AppHeader from "@/components/ui/AppHeader.vue";
import {time} from '@/utils/time.js';

export default {
    name: "tournament.edit",
    components: {
        AppStageCreate, AppLoader, AppEditor,
        AppHeader,
    },
    setup() {
        const route = useRoute();
        const store = useStore();

        const h1 = ref();
        const title = ref();
        const yacht = ref();
        const description = ref();
        const loading = ref(false);
        const stages = ref();
        const id = route.params.id;

        const addStage = (submitData) => {
            stages.value.unshift({
                id: submitData.id,
                race_start: submitData.race_start,
                title: submitData.title,
            });
        }

        onMounted(async () => {
            try {
                const tournament = await axios.get(`/api/admin/tournament/${id}`);
                title.value = h1.value = tournament.data.title;
                yacht.value = tournament.data.yacht;
                description.value = tournament.data.description ? tournament.data.description : ' ';
                const stageData = await axios.get(`/api/admin/stage/${id}`);
                stages.value = stageData.data;
            } catch (e) {
                console.log(e.message);
            }
        });

        const submit = async () => {
            loading.value = true;
            try {
                 await axios.patch(`/api/admin/tournament/${id}/update`, {
                    title: title.value,
                    yacht: yacht.value,
                    description: description.value,
                });
                store.dispatch('notification/displayMessage', {
                    value: 'Серия успешно обновлена',
                    type: 'primary',
                });
            } catch (e) {
                console.log(e.message);
                store.dispatch('notification/displayMessage', {
                    value: 'Ошибка при обновлении серии',
                    type: 'error',
                });
            }
            loading.value = false;
        };


        return {
            submit, title, yacht,
            description, loading, stages,
            addStage, h1, time,
        }
    }
}
</script>

<style scoped>

</style>
