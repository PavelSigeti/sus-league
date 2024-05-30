<template>
    <div class="dashboard-item"  v-if="invites.length > 0">
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
                    <div class="btn btn-default btn-team btn-accept" @click="acceptInvite(invite.id)" title="Принять">
                        <i class="ri-check-line"></i>
                    </div>
                    <div class="btn btn-border btn-team btn-cancel" @click="rejectInvite(invite.id, idx)" title="Отказаться">
                        <i class="ri-close-line"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {onMounted, ref} from "vue";
import axios from "axios";
import {useStore} from "vuex";

const invites = ref([]);
const store = useStore();
const props = defineProps(['load']);
const emit = defineEmits(['load', 'update']);

onMounted(async () => {
    const resp = await axios.get('/api/team-invite/show');
    invites.value = resp.data.invites;
});

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

</script>

<style scoped>
.team-invites {
    margin-top: 15px;
    h3 {
        margin-bottom: 15px;
    }
}
.team-invite__item {
    box-shadow: 0px 0px 30px 5px rgba(0, 0, 0, 0.1);
    background: #fff;
}
.team-invite__buttons .btn-default {
    margin-right: 10px;
}
</style>
