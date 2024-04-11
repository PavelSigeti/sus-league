<template>
    <AppHomeHeader />
    <main>
        <div class="container">
            <div class="row">
                <div class="col-12 mt30 mb30">
                    <h1>Рейтинг кубка</h1>
                </div>
<!--                <div class="col-12">-->
<!--                    <div class="tabs">-->
<!--                        <div-->
<!--                            :class="['tab-item', {'tab-item__active': section === 'user'}]"-->
<!--                            @click="section = 'user'"-->
<!--                        >Личный зачет</div>-->
<!--                        <div-->
<!--                            :class="['tab-item', {'tab-item__active': section === 'team'}]"-->
<!--                            @click="section = 'team'"-->
<!--                        >Командный зачет</div>-->
<!--                        <div-->
<!--                            :class="['tab-item', {'tab-item__active': section === 'university'}]"-->
<!--                            @click="section = 'university'"-->
<!--                        >Университетский зачет</div>-->
<!--                    </div>-->
<!--                </div>-->
                <div class="col-12">
                    <keep-alive>
                        <AppUsersRating v-if="section === 'user'" />
                    </keep-alive>
                    <keep-alive>
                        <AppTeamRating v-if="section === 'team'" />
                    </keep-alive>
                    <keep-alive>
                        <AppUniversityRating v-if="section === 'university'" />
                    </keep-alive>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
import {ref} from 'vue';
import AppHomeHeader from "../components/ui/AppHomeHeader.vue";
import AppUsersRating from "../components/public/AppUsersRating.vue";
import AppTeamRating from "../components/public/AppTeamRating.vue";
import AppUniversityRating from "../components/public/AppUniversityRating.vue";

export default {
    name: "HomeRating",
    components: {
        AppUsersRating, AppTeamRating, AppUniversityRating,
        AppHomeHeader,
    },
    setup() {
        const section = ref('user');

        return {
            section,
        }
    }
}
</script>

<style scoped>

</style>
