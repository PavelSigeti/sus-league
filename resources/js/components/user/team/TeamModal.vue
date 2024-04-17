<template>
    <div class="team-modal" ref="target">
        <div class="team-modal__item" @click="emit('open')">{{props.open ? 'Закрыть' : 'Открыть'}}</div>
        <div class="team-modal__item" @click="emit('remove')">{{owner ? 'Расформировать команду' : 'Покинуть команду'}}</div>
    </div>
</template>

<script setup>
import { onClickOutside } from '@vueuse/core';
import {ref} from "vue";

const emit = defineEmits(['remove', 'open', 'modal']);
const props = defineProps(['open', 'owner']);

const target = ref(null);
onClickOutside(target, () => emit('modal'));
</script>

<style scoped>
.team-modal {
    background: #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    position: absolute;
    height: fit-content;
    width: 180px;
    top: 20px;
    z-index: 50;
    right: 15px;
    border-radius: 10px;
    overflow: hidden;
    font-size: .95em;
    box-sizing: border-box;
}
.team-modal__item {
    padding: 10px;
    cursor: pointer;
    transition: .3s;

    &:hover {
        background: #eee;
    }
}
</style>
