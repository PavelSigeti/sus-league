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
            <TeamInvites />
        </div>

        <AppCreateTeam
            v-if="teams.length < 3"
            @loading="(payload) => {loading = payload}"
            @loadData="getData()"
        />

<!--        <AppUserSearchForm-->
<!--            v-if="owner && teamInvites && teammates && teamInvites.length < 3 - teammates.length"-->
<!--            :team_id="team.id"-->
<!--            @invite="addInvite"-->
<!--            @load="toggleLoad"-->
<!--        />-->
<!--        <button class="btn btn-border btn-full-width" v-if="owner" @click="leaveConfirm = true">Расформировать команду</button>-->
<!--        <AppConfirmation v-if="owner && leaveConfirm"-->
<!--                         @confirmation="deleteTeam"-->
<!--                         @close="leaveConfirm = false"-->
<!--                         question="Расформировать команду?"-->
<!--        />-->
<!--        <button-->
<!--            v-if="!owner && team"-->
<!--            class="btn btn-border btn-full-width"-->
<!--            @click="leaveConfirm = true"-->
<!--        >-->
<!--            Покинуть команду-->
<!--        </button>-->
<!--        <AppConfirmation v-if="!owner && leaveConfirm"-->
<!--                         @confirmation="removeTeammate(null, null)"-->
<!--                         @close="leaveConfirm = false"-->
<!--                         question="Покинуть команду?"-->
<!--        />-->
    </div>
    <div class="dashboard-item" v-if="invites && invites.length > 0">
        <AppTeamInvite
            :invites="invites"
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
// const invites = ref();
// const owner = ref(false);
// const teammates = ref([]);
// const teamInvites = ref();
const loading = ref(false);
// const leaveConfirm = ref(false);

const toggleLoad = () => {
    loading.value = !loading.value;
};

const getData = async () => {
    loading.value = true;
    try {
        const response = await axios.get('/api/team/show');
        teams.value = response.data;
        // invites.value = response.data.invites;
        // owner.value = response.data.owner;
        // teammates.value = response.data.teammates;
        // teamInvites.value = response.data.teamInvites;
    } catch (e) {
        console.log(e.message);
    }
    loading.value = false;
};

onMounted(async() => {
    await getData();
});

const cancelInvite = async (id, idx) => {
    loading.value = true;
    try {
        await axios.delete(`/api/team-invite/${id}/delete`);
        teamInvites.value.splice(idx, 1);
        store.dispatch('notification/displayMessage', {
            value: 'Приглашение отменено',
            type: 'primary',
        });
    } catch (e) {
        console.log(e.message);
        store.dispatch('notification/displayMessage', {
            value: e.message,
            type: 'error',
        });
    }
    loading.value = false;
};

const addInvite = (payload) => {
    teamInvites.value.push(payload);
};

const deleteTeam = async () => {
    loading.value = true;
    try {
      await axios.delete(`/api/team/delete`);
      store.dispatch('notification/displayMessage', {
          value: 'Команда расформирована!',
          type: 'primary',
      });
      await getData();
    }  catch (e) {
      console.log(e.message);
      store.dispatch('notification/displayMessage', {
          value: e.response.data.message,
          type: 'error',
      });
    }
    leaveConfirm.value = false;
    loading.value = false;
};
</script>

<style scoped>
.team-container:last-child {
    margin-bottom: 0;
}
</style>
