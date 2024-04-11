<template>
    <div class="dash-stage">
        <AppLoader v-if="loading" />
        <div class="dash-stage__header">
            <router-link :to="`/dashboard/stage/${stage.id}`" class="dash-stage__title">{{stage.title}}</router-link>
            <div class="dash-stage__tournament">{{stage.tournament}}</div>
            <div class="user-stage__participant" v-if="stage.users_exists">Вы участвуете</div>
        </div>
        <div class="dash-stage__date">
            <span>Начало регистрации: {{time(stage.register_start)}}</span>
            <span>Окончание регистрации: {{time(stage.register_end)}}</span>
            <span>Начало гонок: {{time(stage.race_start)}}</span>
        </div>
        <div class="btn btn-disable btn-settings-280" v-if="time(stage.register_start) > now">
            Регистрация не началась
        </div>
        <div class="btn btn-disable btn-settings-280" v-else-if="time(stage.race_start) < now && stage.status !== 'finished'">
            Регата проходит
        </div>
        <div
            v-else-if="stage.status === 'active' && time(stage.register_end) > now"
             :class="['btn', 'btn-settings-280', {'btn-default': !stage.users_exists}, {'btn-border': stage.users_exists}]"
             @click="toggleReg"
        >
            {{stage.users_exists ? 'Отказаться от участия' : 'Принять участие'}}
        </div>
        <div class="btn btn-disable btn-settings-280" v-else>Ожидайте</div>
    </div>
</template>

<script>
import {onBeforeUnmount, onMounted, ref} from 'vue';
import AppLoader from "../../ui/AppLoader.vue";
import {time} from "@/utils/time.js";
import {useStore} from "vuex";

export default {
    name: "AppDashStages",
    components: {
        AppLoader,
    },
    props: [
        'stage',
    ],
    setup(props) {
        const getDate = () => {
            const dateArr = new Date().toLocaleString("ru-RU", {timeZone: "Europe/Moscow"}).split(',');
            now.value = `${dateArr[0]} ${dateArr[1]}`;
        }

        const store = useStore();
        const stage = ref(props.stage);

        const loading = ref(false);

        const now = ref();
        onMounted(()=>{
            getDate();
        });

        const updateTimeInterval = setInterval(getDate, 1000);
        onBeforeUnmount(() => {
            clearInterval(updateTimeInterval);
        });

        const toggleReg = async () => {
            loading.value = true;
            try {
                if(!stage.value.users_exists) {
                    await axios.post(`/api/stage/${stage.value.id}/accept`);
                    stage.value.users_exists = true;
                    store.dispatch('notification/displayMessage', {
                        value: 'Вы зарегестированы на регату',
                        type: 'primary',
                    });
                } else {
                    await axios.post(`/api/stage/${stage.value.id}/cancel`);
                    stage.value.users_exists = false;
                    store.dispatch('notification/displayMessage', {
                        value: 'Вы успешно отказались от участие в регате',
                        type: 'primary',
                    });
                }
            } catch (e) {
                console.log(e.message);
                store.dispatch('notification/displayMessage', {
                    value: e.response.data.message,
                    type: 'error',
                });
            }
            loading.value = false;
        };

        return {
            stage, loading, time,
            toggleReg, now,
        };
    }
}
</script>

<style scoped>

</style>
