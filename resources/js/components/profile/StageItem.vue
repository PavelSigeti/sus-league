<template>
    <div class="stage-list__item">
        <div class="list-item__info">
            <div class="list-item__title">{{ stage.title }}</div>
            <div class="list-item__subtitle">{{ stage.tournament }}</div>
            <div class="list-item__date">Дата проведения: {{ stage.date }}</div>
        </div>
        <div class="list-item__result-row">
            Количество команд: {{ stage.participants }}
        </div>
        <button 
            class="btn btn-border btn-settings-280 mt10"
            @click="goToStage"
        >
            Подробнее
        </button>
    </div>
</template>

<script setup>
import { useRouter } from "vue-router";

const router = useRouter();

const props = defineProps({
    stage: {
        type: Object,
        required: true
    }
});

const goToStage = () => {
    router.push(`/dashboard/stage/${props.stage.id}`);
};
</script>


<style scoped>
.stage-list__item {
    display: flex;
    flex-direction: column;
    gap: 10px;
    font-family: Jost;
    font-style: normal;
    line-height: normal;
    font-weight: 400;
    color: #242424;
    padding: 15px;
    box-shadow: 0px 0px 10px 0px rgba(0,0,0,.15);
    border-radius: 10px;
    height: fit-content;
    box-sizing: border-box;
    width: 100%;
    max-width: 500px;
}

.list-item__info {
    display: flex;
    gap: 2px;
    flex-direction: column;
}

.list-item__title {
    font-size: 17px;
    font-weight: 500;
    white-space: nowrap;
    overflow: hidden;        
    text-overflow: ellipsis;
}

.list-item__subtitle {
    color: #555;
    font-size: 15px;
}

.list-item__date {
    font-size: 15px;
}

.list-item__result-row {
    font-size: 16px;
}

.list-item__result {
    font-size: 16px;
    font-weight: 500;
}

.btn {
    width: 90%;
    max-width: 300px;
}
</style>
