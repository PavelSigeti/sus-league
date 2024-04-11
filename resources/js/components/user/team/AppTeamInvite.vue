<template>
    <div class="team-invites">
        <h3>Приглашения в команду</h3>
        <div
            class="team-invite__item"
            v-for="(invite, idx) in invites"
            :key="invite.id"
        >
            <div class="team-invite__item-name">
                Команда: <span class="b500">{{invite.name}}</span>
            </div>
            <div class="team-invite__buttons">
                <div class="btn btn-default btn-team btn-accept" @click="acceptInvite(invite.id)">
                    Принять
                </div>
                <div class="btn btn-border btn-team btn-cancel" @click="rejectInvite(invite.id, idx)">
                    Откзаться
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {ref} from "vue";
import axios from "axios";
import {useStore} from "vuex";

export default {
    name: "AppTeamInvite",
    props: ['invites', 'load'],

    setup(props, {emit}) {
        const invites = ref(props.invites);
        const store = useStore();

        const rejectInvite = async (id, idx) => {
            emit('load');
            try {
                await axios.delete(`/api/team-invite/${id}/delete`);
                invites.value.splice(idx, 1);
                store.dispatch('notification/displayMessage', {
                    value: 'Приглашение отклонено',
                    type: 'primary',
                });
                emit('load');
            } catch (e) {
                emit('load');
                store.dispatch('notification/displayMessage', {
                    value: 'Ошибка при отклонении приглашения',
                    type: 'error',
                });
            }
        };
        const acceptInvite = async (id) => {
            emit('load');
            try {
                await axios.post(`/api/team-invite/${id}/accept`);
                invites.value = [];
                store.dispatch('notification/displayMessage', {
                    value: 'Вы вступили в команду',
                    type: 'primary',
                });
                emit('update');
                emit('load');
            } catch (e) {
                console.log(e.message);
                store.dispatch('notification/displayMessage', {
                    value: 'Ошибка при вступлении в команду',
                    type: 'error',
                });
                emit('load');
            }
        };

        return {
            invites, rejectInvite, acceptInvite
        }
    }
}
</script>

<style scoped>

</style>
