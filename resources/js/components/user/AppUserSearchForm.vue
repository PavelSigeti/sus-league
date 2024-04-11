<template>
    <form class="invite-form" @submit.prevent>
        <div class="user-item" v-if="!selected">
            <div class="user-item__content">
                <input
                    class="form-input__user-search"
                    type="text"
                    id="user"
                    v-model="user"
                    @input="displaySearch = false"
                    placeholder="Найти пользователя"
                    autocomplete="off"
                >
            </div>
            <div class="user-item__btn">
                <div @click="search" :class="['btn', 'btn-team', {'btn-default': !displaySearch,'btn-border': displaySearch, }]">{{ displaySearch ? 'Отчистить' : 'Найти'}}</div>
            </div>
        </div>
        <div class="user-item" v-else>
            <div class="user-item__content">
                <div class="user-item__nickname">
                    {{selected.nickname}}
                    <span class="user-item__id">#{{selected.id}}</span>
                </div>
                <div class="user-item__name">
                    {{selected.surname}} {{selected.name}}
                </div>
                <div class="user-item__cancel" @click="selected = null">Убрать</div>
            </div>
            <div class="user-item__invite" @click="selected = null">
                <button @click.prevent="invite" class="btn btn-default btn-team">Пригласить</button>
            </div>
        </div>
        <div class="search-container" v-if="searchCandidates && user.length > 0 && displaySearch">
            <div class="search-candidate"
                 v-for="user in searchCandidates"
                 :key="user.id"
                 @click="selectUser(user)"
            >
                <div class="search-candidate__nickname">
                    {{user.nickname}}
                    <span class="search-candidate__id">#{{user.id}}</span>
                </div>
                <div class="search-candidate__name">
                    {{user.surname}} {{user.name}}
                </div>
            </div>
        </div>
    </form>
</template>

<script>
import axios from "axios";
import {ref} from "vue";
import {useStore} from "vuex";

export default {
    name: "TheUserSearchForm",
    props: ['team_id',],
    emits: ['invite', 'load'],
    setup(props, {emit}) {
        const store = useStore();

        const user = ref();
        const searchCandidates = ref(null);
        const selected = ref(null);

        const displaySearch = ref(false);

        const search = async () => {
            if(displaySearch.value === true) {
                user.value = '';
                displaySearch.value = false;
                return;
            }
            if(user.value.length > 0) {
                const response = await axios.post('/api/user/search', {
                    user: user.value
                });
                if(response.data.length > 0) {
                    searchCandidates.value = response.data;
                    displaySearch.value = true;
                } else {
                    searchCandidates.value = null;
                }
            } else {
                searchCandidates.value = null;
            }
        };

        const selectUser = (payload) => {
            user.value = '';
            displaySearch.value = false;
            searchCandidates.value = null;
            selected.value = payload;
        };

        const invite = async () => {
            try {
                emit('load');
                if(selected.value) {
                    const response = await axios.post('/api/team-invite', {
                        team_id: props.team_id,
                        user_id: selected.value.id,
                    });
                    selected.value = null;
                    store.dispatch('notification/displayMessage', {
                        value: 'Прглашение отправлено',
                        type: 'primary',
                    });
                    emit('invite', response.data);
                    emit('load');
                }
                else {
                    store.dispatch('notification/displayMessage', {
                        value: 'Выберите пользователя',
                        type: 'error',
                    });
                    emit('load');
                }
            } catch (e) {
                console.log(e.message);
                store.dispatch('notification/displayMessage', {
                    value: e.response.data.message,
                    type: 'error',
                });
                emit('load');
            }
        };

        return {
            search, searchCandidates, user,
            invite, selected, selectUser,
            displaySearch,
        }
    }
}
</script>

<style scoped>

</style>
