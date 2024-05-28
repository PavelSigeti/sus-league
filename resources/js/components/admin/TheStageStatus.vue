<template>
    <button class="btn btn-default btn-settings mt15" @click="finishGroupStage" v-if="status === 'group'">Завершить групповой этап</button>
<!--    <button class="btn btn-default btn-settings" @click="finishStage" v-if="status === 'default' || status === 'fleet'">Завершить регату</button>-->
</template>

<script setup>
import { ref } from 'vue';
import axios from "axios";
import {useStore} from 'vuex';

const props = defineProps(['status', 'id']);
const emit = defineEmits(['update']);
const status = ref(props.status);
const id = props.id;
const store = useStore();

const finishGroupStage = async () => {
    try {
        const response = await axios.post(`/api/admin/stage/${id}/finish-group`);
        status.value = response.data.status;
        emit('update', status.value);
    } catch (e) {
        console.log(e.message);
        store.dispatch('notification/displayMessage', {
            value: e.response.data.message,
            type: 'error',
        });
    }
};

const finishStage = async () => {
    try {
        const response = await axios.post(`/api/admin/stage/${id}/finish`);
        status.value = response.data.status;
        emit('update', status.value);
    } catch (e) {
        console.log(e.message);
        store.dispatch('notification/displayMessage', {
            value: e.response.data.message,
            type: 'error',
        });
    }
};



</script>

<style scoped>

</style>
