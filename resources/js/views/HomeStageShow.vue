<template>
    <AppHomeHeader />
    <main>
        <div class="container">
            <div class="row">
                <div class="col-12 mt30">
                    <h1 class="mb30">{{stage.title}}</h1>
                    <div class="dashboard-item" v-if="stage && stage.register_start">
                        <div class="user-stage__date mb0" >
                            <span>Начало регистрации: {{time(stage.register_start)}}</span>
                            <span>Окончание регистрации: {{time(stage.register_end)}}</span>
                            <span>Начало гонок: {{time(stage.race_start)}}</span>
                        </div>
                    </div>
                    <div class="dashboard-item" v-if="stage && stage.description">
                        <div class="content" v-html="stage.description"></div>
                    </div>
                    <div class="dashboard-item" v-if="stage && stage.status === 'active'">
                        <AppUsersTables :users="stage.users"  />
                    </div>
                    <div class="dashboard-item" v-if="stage && stage.status !== 'active'">
                        <AppResultTable :id="id" />
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
import AppResultTable from "@/components/public/AppResultTable.vue";
import AppUsersTables from "@/components/ui/AppUsersTables.vue";
import AppHomeHeader from "../components/ui/AppHomeHeader.vue";
import {onMounted, ref} from "vue";
import {useRoute} from "vue-router";
import {time} from "@/utils/time.js";

export default {
    name: "HomeStageShow",
    components: {
        AppResultTable, AppUsersTables, AppHomeHeader,
    },
    setup() {
        const stage = ref({});
        const route = useRoute();
        const id = route.params.id;
        onMounted(async () => {
            try {
                const response = await axios.get(`/api/stage/${id}/show`);
                stage.value = response.data;
                h1.value = stage.value.title;
            } catch (e) {
                console.log(e.message);
            }
        });

        return {
            stage, id, time,
        }
    }
}
</script>

<style scoped>

</style>
