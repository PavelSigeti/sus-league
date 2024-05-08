<template>
    <div class="dashboard-item">
        <AppLoader v-if="loading" />
        <h3>Команды</h3>
        <div class="content-block  team-content" v-if="teams" >
            <TeamContainer v-for="team in teams" :key="team.id"
                           :team="team"
                           @loading="(payload) => {loading = payload}"
                           @update="getData()"
            />
        </div>

        <div class="content-block">
            <AppTeamInvite @update="getData()" />
        </div>

        <AppCreateTeam
            v-if="teams.length < 3"
            @loading="(payload) => {loading = payload}"
            @loadData="getData()"
        />

    </div>
    <div class="dashboard-item" v-if="invites && invites.length > 0">
        <AppTeamInvite
            @update="getData"
            @load="toggleLoad"
        />
    </div>
</template>

<script setup>
import {onMounted, ref} from "vue";
import axios from "axios";
import AppUserSearchForm from "../AppUserSearchForm.vue";
import AppTeamInvite from "./AppTeamInvite.vue";
import { useStore } from "vuex";
import AppLoader from "../../ui/AppLoader.vue";
import AppConfirmation from "../../ui/AppConfirmation.vue";
import AppCreateTeam from "../../ui/AppCreateTeam.vue";
import TeamContainer from "@/components/user/team/TeamContainer.vue";

const store = useStore();

const teams = ref([]);
const loading = ref(false);

const toggleLoad = () => {
    loading.value = !loading.value;
};

const getData = async () => {
    loading.value = true;
    try {
        const response = await axios.get('/api/team/show');
        teams.value = response.data;
    } catch (e) {
        console.log(e.message);
    }
    loading.value = false;
};

onMounted(async() => {
    await getData();
});

const addInvite = (payload) => {
    teamInvites.value.push(payload);
};
</script>

<style scoped>
.team-container:last-child {
    margin-bottom: 0;
}
</style>
