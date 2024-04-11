<template>
    <div class="dashboard-item">
        <AppLoader v-if="loading" />
        <h3>Команда</h3>
        <div class="content-block" v-if="team">
            <p>Ваша команда: <span class="b500">{{team.name}}</span></p>
                <div class="teammate-container">
                    <div class="user-item" v-for="(user, idx) in teammates" :key="user.id">
                        <div class="user-item__content">
                            <div class="user-item__nickname">
                                {{user.nickname}}
                                <span class="user-item__id">#{{user.id}}</span>
                            </div>
                            <div class="user-item__name">
                                {{user.surname}} {{user.name}}
                            </div>
                        </div>
                        <div class="user-item__icon" v-if="team.owner_id === user.id">
                            <img src="@/static/crown.svg" alt="crown">
                        </div>
                        <div
                            class="user-item__btn user-item__cancel-btn"
                            v-if="owner && team.owner_id !== user.id"
                            @click="removeTeammate(user.id, idx)"
                        >
                            <div class="btn btn-border btn-team btn-cancel">Исключить</div>
                        </div>
                    </div>
                </div>
        </div>

        <AppCreateTeam
            v-else
            @loading="(payload) => {loading = payload}"
            @loadData="getData()"
        />

        <div class="team-invites" v-if="teamInvites && teamInvites.length > 0">
            <div
                class="user-item"
                v-for="(invite, idx) in teamInvites"
                :key="invite.id"
            >
                <div class="user-item__content">
                    <div class="user-item__nickname">
                        {{invite.nickname}}
                        <span class="user-item__id">#{{invite.user_id}}</span>
                    </div>
                    <div class="user-item__name">
                        {{invite.surname}} {{invite.name}}
                    </div>
                </div>
                <div class="user-item__btn" @click="cancelInvite(invite.id, idx)">
                    <div class="btn btn-border btn-team btn-cancel">Отменить</div>
                </div>
            </div>
        </div>

        <AppUserSearchForm
            v-if="owner && teamInvites && teammates && teamInvites.length < 3 - teammates.length"
            :team_id="team.id"
            @invite="addInvite"
            @load="toggleLoad"
        />
        <button class="btn btn-border btn-full-width" v-if="owner" @click="leaveConfirm = true">Расформировать команду</button>
        <AppConfirmation v-if="owner && leaveConfirm"
                         @confirmation="deleteTeam"
                         @close="leaveConfirm = false"
                         question="Расформировать команду?"
        />
        <button
            v-if="!owner && team"
            class="btn btn-border btn-full-width"
            @click="leaveConfirm = true"
        >
            Покинуть команду
        </button>
        <AppConfirmation v-if="!owner && leaveConfirm"
                         @confirmation="removeTeammate(null, null)"
                         @close="leaveConfirm = false"
                         question="Покинуть команду?"
        />
    </div>
    <div class="dashboard-item" v-if="invites && invites.length > 0">
        <AppTeamInvite
            :invites="invites"
            @update="getData"
            @load="toggleLoad"
        />
    </div>
</template>

<script>
import {onMounted, ref} from "vue";
import axios from "axios";
import AppUserSearchForm from "../AppUserSearchForm.vue";
import AppTeamInvite from "./AppTeamInvite.vue";
import { useStore } from "vuex";
import AppLoader from "../../ui/AppLoader.vue";
import AppConfirmation from "../../ui/AppConfirmation.vue";
import AppCreateTeam from "../../ui/AppCreateTeam.vue";

export default {
    name: "TheTeamSettings",
    components: {
        AppUserSearchForm, AppTeamInvite, AppLoader,
        AppConfirmation, AppCreateTeam,
    },
    setup() {
        const store = useStore();

        const team = ref();
        const invites = ref();
        const owner = ref(false);
        const teammates = ref([]);
        const teamInvites = ref();
        const loading = ref(false);
        const leaveConfirm = ref(false);

        const toggleLoad = () => {
            loading.value = !loading.value;
        };

        const getData = async () => {
            loading.value = true;
            try {
                const response = await axios.get('/api/team/edit');
                team.value = response.data.team;
                invites.value = response.data.invites;
                owner.value = response.data.owner;
                teammates.value = response.data.teammates;
                teamInvites.value = response.data.teamInvites;
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

        const removeTeammate = async (id, idx) => {
            loading.value = true;
            try {
                await axios.post(`/api/user/remove-teammate`, {id});
                if(id === null) {
                    await getData();
                } else {
                    teammates.value.splice(idx, 1);
                }
                store.dispatch('notification/displayMessage', {
                    value: 'Пользователь покинул команду',
                    type: 'primary',
                });
                leaveConfirm.value = false
            } catch (e) {
                console.log(e.message);
                store.dispatch('notification/displayMessage', {
                    value: e.response.data.message,
                    type: 'error',
                });
            }
            loading.value = false;
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

        return {
            name, team, owner,
            cancelInvite, teammates, invites,
            getData, removeTeammate, deleteTeam,
            loading, toggleLoad, leaveConfirm,
            addInvite, teamInvites,
        }
    }
}
</script>

<style scoped>

</style>
