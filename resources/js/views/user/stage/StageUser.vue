<template>
    <AppHeader>Регаты</AppHeader>
    <main>
        <div class="container-fluid g-0">
            <div class="row">
                <div class="col-12">
                    <div class="tabs">
                        <div
                            :class="['tab-item', {'tab-item__active': section === 'actual'}]"
                            @click="section = 'actual'"
                        >Актуальные регаты</div>
                        <div
                            :class="['tab-item', {'tab-item__active': section === 'registered'}]"
                            @click="section = 'registered'"
                        >Мои регаты</div>
                        <div
                            :class="['tab-item', {'tab-item__active': section === 'ended'}]"
                            @click="section = 'ended'"
                        >Прошедшие регаты</div>
                    </div>
                </div>
                <div class="col-12">
                        <AppActualStage v-if="section === 'actual'" />
                        <AppRegisteredStage v-if="section === 'registered'" />
                        <AppEndedStage v-if="section === 'ended'" />
                </div>
            </div>
        </div>
    </main>
</template>

<script>
import AppHeader from "@/components/ui/AppHeader.vue";
import AppActualStage from "@/components/user/stage/AppActualStage.vue";
import AppRegisteredStage from "@/components/user/stage/AppRegisteredStage.vue";
import AppEndedStage from "@/components/user/stage/AppEndedStage.vue";
import {ref} from 'vue';

export default {
    name: "StageUser",
    components: {
        AppHeader, AppActualStage, AppRegisteredStage,
        AppEndedStage,
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
