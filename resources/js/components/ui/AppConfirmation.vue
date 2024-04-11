<template>
    <div class="modal">
        <div class="modal-background" @click="$emit('close')"></div>
        <div class="modal-container">
            <h3>{{question}}</h3>
            <div class="btn btn-cancel btn-border btn-full-width mb15" @click="$emit('confirmation')">Да</div>
            <div class="btn btn-accept btn-default btn-full-width" @click="$emit('close')">Нет</div>
        </div>
    </div>
</template>

<script>
import { ref } from 'vue';

export default {
    name: "AppConfirmation",
    emits: [
        'close', 'confirmation',
    ],
    props: [
        'question',
    ],
    setup(props,) {
        const question = ref(props.question);

        return {
            question,
        }
    }
}
</script>

<style scoped>

</style>
