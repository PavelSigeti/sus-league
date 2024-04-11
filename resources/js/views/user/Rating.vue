<template>
    <AppHeader>Рейтинг кубка</AppHeader>
    <main>
        <div class="container-fluid g-0">
            <div class="row">
                <div class="col-12">
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
                </div>
                <div class="col-12">
                    <AppUsersRating v-if="section === 'user'" />

                    <!--                    <keep-alive>-->
<!--                        <AppTeamRating v-if="section === 'team'" />-->
<!--                    </keep-alive>-->
<!--                    <keep-alive>-->
<!--                        <AppUniversityRating v-if="section === 'university'" />-->
<!--                    </keep-alive>-->
                </div>
            </div>
        </div>
    </main>
</template>

<script>
import AppHeader from "../../components/ui/AppHeader.vue";
import AppUsersRating from "../../components/public/AppUsersRating.vue";
import AppTeamRating from "../../components/public/AppTeamRating.vue";
import AppUniversityRating from "../../components/public/AppUniversityRating.vue";
import { ref } from 'vue';

export default {
    name: "Rating.vue",
    components: {
        AppHeader, AppUsersRating, AppTeamRating,
        AppUniversityRating,
    },
    setup() {
        const section = ref('user');


        return {
            section,
        };
    },
}
</script>

<style scoped>

</style>
