<template>
    <AppHomeHeader />
    <main>
        <div class="container">
            <div class="row">
                <div class="col-12 mt30 mb30">
                    <h1>Результаты регат</h1>
                </div>
                <div class="col-12">
                    <div class="tabs">
                        <div
                            :class="['tab-item', {'tab-item__active': section === 'actual'}]"
                            @click="section = 'actual'"
                        >Актуальные регаты</div>
                        <div
                            :class="['tab-item', {'tab-item__active': section === 'ended'}]"
                            @click="section = 'ended'"
                        >Прошедшие регаты</div>
                    </div>
                </div>
                <div class="col-12">
                    <AppHomeEndedStage v-if="section === 'ended'" />
                    <AppHomeActualStage v-if="section === 'actual'" />
                </div>
            </div>
        </div>
    </main>
</template>

<script>
import AppHomeEndedStage from "../components/public/AppHomeEndedStage.vue";
import AppHomeActualStage from "../components/public/AppHomeActualStage.vue";
import AppHomeHeader from "../components/ui/AppHomeHeader.vue";
import { ref } from 'vue';

export default {
    name: "HomeStage",
    components: {
        AppHomeEndedStage, AppHomeActualStage, AppHomeHeader,
    },
    setup() {
        const section = ref('actual');

        return {
            section,
        }
    }
}
</script>

<style scoped>

</style>

HomeStage
