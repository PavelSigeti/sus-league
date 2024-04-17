<template>
    <div class="team-invites" v-if="teamInvites && teamInvites.length > 0">
        <div
            class="user-item"
            v-for="(invite, idx) in teamInvites"
            :key="invite.id"
        >
            <div class="user-item__content">
                <div class="user-item__name">
                    {{invite.surname}} {{invite.name}} {{invite.patronymic}}
                </div>
            </div>
            <div class="user-item__btn" @click="cancelInvite(invite.id, idx)">
                <div class="btn btn-border btn-team btn-cancel" title="Отменить"><i class="ri-close-large-line"></i></div>
            </div>
        </div>
    </div>
</template>

<script setup>

import {onMounted, ref} from "vue";

const teamInvites = ref([]);

onMounted(async () => {
    const resp = await axios.get('/api/team-invite/show');
    teamInvites.value = resp.data.invites;
});
</script>

<style scoped>

</style>
