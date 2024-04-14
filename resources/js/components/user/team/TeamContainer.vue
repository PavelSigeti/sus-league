<template>
    <div class="team-container">
        <div class="team-head b500">
            <div class="team-head__title">{{team.name}}</div> <i class="ri-settings-3-line"></i>
        </div>
        <div class="teammate-container">
            <div class="user-item" v-for="(user, idx) in teammates" :key="user.id">
                <div class="user-item__content">
                    <div class="user-item__name">{{user.surname}} {{user.name}} {{user.patronymic}}</div>
                </div>
                <div class="user-item__icon" v-if="team.owner_id === user.id">
                    <img src="@/static/crown.svg" alt="crown">
                </div>
                <div
                    class="user-item__btn user-item__cancel-btn"
                    v-if="owner && team.owner_id !== user.id"
                    @click="removeTeammate(user.id, idx)"
                >
                    <div class="btn btn-border btn-team btn-cancel" title="Исключить"><i class="ri-delete-bin-2-line"></i></div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
const props = defineProps(['team', 'teammates']);
</script>

<style scoped>

</style>
