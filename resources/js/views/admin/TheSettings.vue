<template>
    <AppHeader>Настройки сайта</AppHeader>
    <main>
        <div class="container-fluid g-0">
            <div class="row">
                <TheUniversitiesSettings />
            </div>
        </div>
    </main>
</template>

<script>
import TheUniversitiesSettings from "./settings/TheUniversitiesSettings.vue";
import AppHeader from "@/components/ui/AppHeader.vue";

export default {
    name: "TheSettings",
    components: {
        TheUniversitiesSettings, AppHeader,
    },
    setup() {


        return {

        }
    }
}
</script>

<style scoped>

</style>
