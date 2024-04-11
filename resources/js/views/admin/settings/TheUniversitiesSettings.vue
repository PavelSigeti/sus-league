<template>
    <div class="col-12">
        <div class="dashboard-item">
            <AppLoader v-if="loading"/>
            <h3>Добавить университет</h3>
            <form @submit.prevent="submit" class="mb30">
                <div class="form-control">
                    <label for="name">Название университета</label>
                    <input class="form-input" type="text" id="name" v-model="name" placeholder="Название университета">
                </div>
                <button class="btn btn-default btn-settings">Добавить</button>
            </form>

            <div class="universities-container" v-if="universities && universities.length > 0">
                <h3>Список университетов</h3>
                <div class="item-block" v-for="(item, idx) in universities" :key="item.id">
                    <div class="item-block__content">
                        {{item.name}} - {{item.users_count}} чел.
                    </div>
                    <div class="item-block__btn item-block__cancel-btn">
                        <span @click="removeUniversity(item.id, idx)">Удалить</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {useStore} from "vuex";
import {onMounted, ref} from "vue";
import axios from 'axios';
import AppLoader from "@/components/ui/AppLoader.vue";

export default {
    name: "TheUniversitiesSettings",
    components: {
        AppLoader
    },
    setup() {
        const store = useStore();
        const name = ref();
        const universities = ref();

        const loading = ref(false);

        const removeUniversity = async (id, idx) => {
            loading.value = true;
            try {
                await axios.delete(`/api/admin/universities/${id}/delete`);
                universities.value.splice(idx, 1);
            } catch (e) {
                console.log(e.message);
                store.dispatch('notification/displayMessage', {
                    value: 'Ошибка при удалении университета!',
                    type: 'error',
                });
            }
            loading.value = false;
        }

        const submit = async () => {
            loading.value = true;
            try {
                const response = await axios.post('/api/admin/universities/store', {
                    name: name.value
                });
                const data = response.data;
                data.users_count = 0;
                universities.value.push(data);
            } catch (e) {
                console.log(e.message);
                store.dispatch('notification/displayMessage', {
                    value: 'Ошибка при добавлении университета!',
                    type: 'error',
                });
            }
            loading.value = false;
        };

        onMounted(async () => {
            loading.value = true;
            try {
                const response = await axios.get('/api/admin/universities');
                universities.value = response.data;
            } catch (e) {
                console.log(e.message);
            }
            loading.value = false;
        });

        return {
            universities, submit, name,
            removeUniversity, loading,
        };
    }
}
</script>

<style scoped>

</style>
