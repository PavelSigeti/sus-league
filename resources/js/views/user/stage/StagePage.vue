<template>
    <AppHeader>{{ h1 }}</AppHeader>
    <main>
        <div class="container-fluid g-0">
            <div class="row">
                <div class="col-12">
                    <div class="dashboard-item" v-if="stage && stage.register_start">
                        <div class="user-stage__date mb0" >
                            <span>Начало регистрации: {{time(stage.register_start)}}</span>
                            <span>Окончание регистрации: {{time(stage.register_end)}}</span>
                            <span>Начало гонок: {{time(stage.race_start)}}</span>
                        </div>
                    </div>
                    <div class="dashboard-item" v-if="stage && stage.participant_text && stage.status !== 'active'">
                        <div class="content" v-html="stage.participant_text"></div>
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
import AppHeader from "@/components/ui/AppHeader.vue";
import AppResultTable from "@/components/public/AppResultTable.vue";
import AppUsersTables from "@/components/ui/AppUsersTables.vue";
import {onMounted, ref} from "vue";
import {useRoute} from "vue-router";
import {time} from "@/utils/time.js";

export default {
    name: "StagePage",
    components: {
        AppHeader, AppResultTable, AppUsersTables,
    },
    setup() {
        const stage = ref({});
        const route = useRoute();
        const id = route.params.id;
        const h1 = ref('');
        onMounted(async () => {
            try {
                const response = await axios.get(`/api/stage/${id}/show-users`);
                stage.value = response.data;
                h1.value = stage.value.title;
                console.log(stage.value.status);
            } catch (e) {
                console.log(e.message);
            }
        });

        return {
            stage, id, h1,
            time,
        }
    }
}
</script>

<style scoped>

</style>
