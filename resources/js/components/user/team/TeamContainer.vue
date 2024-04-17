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
                    @click="removeTeammate(user.id, props.team.team_id)"
                >
                    <div class="btn btn-border btn-team btn-cancel" title="Исключить"><i class="ri-delete-bin-2-line"></i></div>
                </div>
            </div>



<!--            <AppUserSearchForm-->
<!--                v-if="owner && teamInvites && teammates && teamInvites.length < 3 - teammates.length"-->
<!--                :team_id="team.id"-->
<!--                @invite="addInvite"-->
<!--            />-->

<!--        <div class="team-invites" v-if="teamInvites && teamInvites.length > 0">-->
<!--            <div-->
<!--                class="user-item"-->
<!--                v-for="(invite, idx) in teamInvites"-->
<!--                :key="invite.id"-->
<!--            >-->
<!--                <div class="user-item__content">-->
<!--                    <div class="user-item__name">-->
<!--                        {{invite.surname}} {{invite.name}} {{invite.patronymic}}-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="user-item__btn" @click="cancelInvite(invite.id, idx)">-->
<!--                    <div class="btn btn-border btn-team btn-cancel" title="Отменить"><i class="ri-close-large-line"></i></div>-->
<!--                </div>-->
<!--            </div>-->
        </div>

    <AppConfirmation v-if="leaveConfirm"
         @confirmation="removeTeammate(props.team.user_id)"
         @close="leaveConfirm = false"
         :question="owner ? 'Расформировать команду?' : 'Покинуть команду?'"
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
const modal = ref(false);

onMounted(async ()=>{
    const response = await axios.get(`/api/team/edit/${props.team.id}`);
    teammates.value = await response.data;
});

const removeTeammate = async (userId) => {
    emit('loading', true);
    console.log(userId, props.team.id);
    try {
        const resp = await axios.post(`/api/team/remove-teammate`, {
            'user_id': userId,
            'team_id': props.team.id
        });
        // if(id === null) {
        //     await getData();
        // } else {
        //     teammates.value.splice(idx, 1);
        // }
        store.dispatch('notification/displayMessage', {
            value: 'Пользователь покинул команду',
            type: 'primary',
        });
        leaveConfirm.value = false;
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

</script>

<style scoped lang="scss">
.team-head__title{
    font-size: 1.2em;
    cursor: pointer;
    user-select: none;
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
</style>
