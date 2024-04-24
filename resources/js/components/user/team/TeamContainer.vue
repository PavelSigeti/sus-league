<template>
    <div :class="['team-container', {'team-open': open}]">
        <div class="team-head b500">
            <div class="team-head__title"  @click="open=!open">{{team.name}}</div> <i @click="modal=true" class="ri-settings-4-fill"></i>
        </div>
        <TeamModal v-if="modal"
               :owner="owner"
               :open="open"
               @open="open=!open; modal=false"
               @remove="leaveConfirm=true"
               @modal="modal=false"
        />
    <div class="teammate-container">
        <div class="user-item" v-for="user in teammates" :key="user.id">
            <div class="user-item__content">
                <div class="user-item__name">{{user.surname}} {{user.name}} {{user.patronymic}}</div>
            </div>
            <div class="user-item__icon" v-if="team.user_id === user.id">
                <img src="@/static/crown.svg" alt="crown">
            </div>
            <div
                class="user-item__btn user-item__cancel-btn"
                v-if="owner && team.user_id !== user.id"
                @click="kickTeammate = user.id"
            >
                <div class="btn btn-border btn-team btn-cancel" title="Исключить"><i class="ri-delete-bin-2-line"></i></div>
            </div>
        </div>

        <div class="team-invites" v-if="teamInvites && teamInvites.length > 0">
            <div
                class="user-item"
                v-for="(invite) in teamInvites"
                :key="invite.id"
            >
                <div class="user-item__content">
                    <div class="user-item__name">
                        {{invite.surname}} {{invite.name}} {{invite.patronymic}}
                    </div>
                </div>
                <div class="user-item__btn" @click="cancelInvite(invite.id)">
                    <div class="btn btn-border btn-team btn-cancel" title="Отменить"><i class="ri-close-line"></i></div>
                </div>
            </div>
        </div>

        <AppUserSearchForm
            v-if="owner"
            :team_id="team.id"
            @update="emit('update'); teamData();"
        />
    </div>

    <AppConfirmation v-if="leaveConfirm"
         @confirmation="removeTeammate(props.team.user_id)"
         @close="leaveConfirm = false"
         :question="owner ? 'Расформировать команду?' : 'Покинуть команду?'"
    />

    <AppConfirmation v-if="kickTeammate !== null"
         @confirmation="removeTeammate(kickTeammate);kickTeammate = null"
         @close="kickTeammate = null"
         question="Исключить из команды?"
    />

</div>
</template>

<script setup>
import {onMounted, ref} from "vue";
import AppUserSearchForm from "@/components/user/AppUserSearchForm.vue";
import AppConfirmation from "../../ui/AppConfirmation.vue";
import {useStore} from "vuex";
import axios from "axios";
import TeamModal from "@/components/user/team/TeamModal.vue";
import TeamInvites from "@/components/user/team/TeamInvites.vue";

const store = useStore();
const props = defineProps(['team']);
const emit = defineEmits(['loading', 'update']);
const teammates = ref([]);
const open = ref(false);
const owner = ref(props.team.user_id === store.getters['auth/user'].id ? true : false);
const leaveConfirm = ref(false);
const kickTeammate = ref(null);
const modal = ref(false);
const teamInvites = ref([]);

const teamData = async () => {
    const response = await axios.get(`/api/team/edit/${props.team.id}`);
    teammates.value = await response.data.users;
    teamInvites.value = await response.data.invites;
}

onMounted(async ()=>{
    await teamData();
});

const removeTeammate = async (userId) => {
    emit('loading', true);
    try {
        const resp = await axios.post(`/api/team/remove-teammate`, {
            'user_id': userId,
            'team_id': props.team.id
        });

        store.dispatch('notification/displayMessage', {
            value: 'Пользователь покинул команду',
            type: 'primary',
        });
        leaveConfirm.value = false;
        await teamData();
        emit('update');
    } catch (e) {
        console.log(e.message);
        store.dispatch('notification/displayMessage', {
            value: e.response.data.message,
            type: 'error',
        });
    }
    emit('loading', false);
};
const cancelInvite = async (id) => {
    emit('loading', true);
    try {
        await axios.delete(`/api/team-invite/${id}/delete`);
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
    await teamData();
    emit('loading', false);
};
</script>

<style scoped lang="scss">
.team-head__title{
    font-size: 1.2em;
    cursor: pointer;
    user-select: none;
    width: 100%;
    height: 30px;
    display: flex;
    align-items: center;
}
.team-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    height: 30px;
    font-size: .9em;
    margin-bottom: 0;
    transition: .3s;
    i {
        color: #BBBBBB;
        font-size: 1.3rem;
        cursor: pointer;
        transition: .3s;
        &:hover {
            color: #777;
        }
    }
}
.team-container {
    position: relative;
    padding: 15px;
    box-shadow: 0px 0px 30px 5px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    margin-bottom: 15px;
}
.team-open .team-head {
    margin-bottom: 15px;
}
.teammate-container {
    max-height: 0px;
    overflow: hidden;
    transition: .5s;
    .user-item:last-child {
        margin-bottom: 0;
    }
}
.team-open .teammate-container {
    max-height: 300px;
    height: fit-content;
    transition: .5s;
}
.team-invites {
    margin-bottom: 15px;
}
</style>
