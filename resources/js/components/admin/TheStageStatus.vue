<template>
    <button class="btn btn-default btn-settings mb30" @click="startStage" v-if="status === 'active'">Начать этап</button>
    <button class="btn btn-default btn-settings mb30" @click="finishGroupStage" v-if="status === 'group'">Завершить групповой этап</button>
    <button class="btn btn-default btn-settings mb30" @click="finishStage" v-if="status === 'default' || status === 'fleet'">Завершить регату</button>
</template>

<script>
import { ref } from 'vue';
import axios from "axios";
import {useStore} from 'vuex';

export default {
    name: "TheStageStatus",
    emits: ['update'],
    props: ['status', 'id'],
    setup(props, { emit }) {
        const status = ref(props.status);
        const id = props.id;
        const store = useStore();

        const startStage = async () => {
            try {
                const response = await axios.post(`/api/admin/stage/${id}/start`);
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

        return {
            status, startStage, finishGroupStage,
            id, finishStage,
        }
    }
}
</script>

<style scoped>

</style>
