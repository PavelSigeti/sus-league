<template>
    <AppHeader>{{ h1 }}</AppHeader>
    <main>
        <div class="container-fluid g-0">
            <div class="row">
                <div class="col-12">
                    <div class="dashboard-item" v-if="stage && stage.register_start">
                        <div class="user-stage__date mb0" >
                            <span>Начало регистрации: {{time(stage.register_start)}}</span>
                            <span>Окончание регистрации: {{time(stage.register_end)}}</span>
                        </div>
                        <div v-if="stage.excerpt" class="user-stage__excerpt content" v-html="stage.excerpt"></div>
                    </div>

                    <div class="dashboard-item" v-if="docs.length > 0">
                        <h3>Документы</h3>
                        <div class="docs-container">
                            <div class="docs-item" v-for="doc in docs" :key="doc.id">
                                <a :href="'/storage/' + doc.path" class="docs-img" target="_blank">
                                    <i class="ri-file-pdf-2-line" v-if="doc.path.includes('pdf')"></i>
                                    <img :src="'/storage/' + doc.path" alt="doc-img" v-else>
                                </a>
                                <div class="docs-title">
                                    {{ doc.title }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="dashboard-item" v-if="stage && stage.participant_text && stage.status !== 'active'">
                        <div class="content" v-html="stage.participant_text"></div>
                    </div>
                    <div class="dashboard-item" v-if="stage && stage.description">
                        <div class="content" v-html="stage.description"></div>
                    </div>
                    <div class="dashboard-item" v-if="stage && stage.status === 'active'">
                        <AppTeamsTabels :id="id"  />
                    </div>
                    <div class="dashboard-item" v-if="stage && stage.status !== 'active'">
                        <AppResultTable :id="id" />
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script setup>
import AppHeader from "@/components/ui/AppHeader.vue";
import AppResultTable from "@/components/public/AppResultTable.vue";
import {onMounted, ref} from "vue";
import {useRoute} from "vue-router";
import {time} from "@/utils/time.js";
import AppTeamsTabels from "@/components/ui/AppTeamsTabels.vue";

const stage = ref({});
const route = useRoute();
const id = route.params.id;
const h1 = ref('');
const docs = ref([]);

onMounted(async () => {
    try {
        const response = await axios.get(`/api/stage/${id}/show-users`);
        stage.value = response.data;
        h1.value = stage.value.title;
    } catch (e) {
        console.log(e.message);
    }

    try {
        const response = await axios.get(`/api/stage/${id}/files`);
        if(response.data.result){
            docs.value = [...response.data.files];
        }
    } catch (e) {
        console.log(e.message);
    }
});
</script>

<style scoped>
.docs-container {
    display: flex;
    gap: 10px;
}
.docs-item {
    height: 180px;
    width: 180px;
    background: #eee;
    border-radius: 10px;
    box-shadow: 0px 0px 5px 0px rgba(0, 0, 0, .25);
    position: relative;
}
.docs-img {
    width: 180px;
    height: 150px;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 10px;
    overflow: hidden;

    img {
        height: 150px;
        width: 180px;
        object-fit: cover;
    }
    i {
        font-size: 2em;
    }
}
.docs-title {
    padding: 5px;
    font-size: .85em;
}
</style>
