<template>
    <ul class="menu">
        <li class="menu-item"><router-link to="/dashboard"><img src="@/static/menu/lk.svg" alt="ЛК"><span>Личный кабинет</span></router-link></li>
        <li class="menu-item"><router-link :to="{name: 'user.stages'}"><img src="@/static/menu/yacht.svg" alt="ЛК"><span>Регаты</span></router-link></li>
        <li class="menu-item"><router-link :to="{name: 'rating'}"><img src="@/static/menu/result.svg" alt=""><span>Рейтинг</span></router-link></li>
        <li class="menu-item"><router-link to="/dashboard/page/reglament"><img src="@/static/menu/rules.svg" alt="ЛК"><span>Регламент</span></router-link></li>
        <li class="menu-item"><router-link to="/dashboard/page/info"><img src="@/static/menu/about.svg" alt="ЛК"><span>Инструкция</span></router-link></li>
    </ul>
    <TheAdminNavbar v-if="admin" />

</template>

<script>
import { useStore } from 'vuex';
import '@/utils/remixicon/remixicon.css';
import TheAdminNavbar from '@/components/admin/TheAdminNavbar.vue';

export default {
    name: "TheNavbar",
    components: {
        TheAdminNavbar,
    },
    setup() {
        const store = useStore();

        const admin = store.getters['auth/user'].role === 'admin';

        return {
             admin,
        }
    }
}
</script>
