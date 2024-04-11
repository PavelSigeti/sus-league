<template>
    <div :class="['notification ',`notification-${data.type}`]">
        {{data.message}}
        <div class="notification-progress"></div>
    </div>
</template>

<script>
import { ref } from 'vue';

export default {
    name: "TheNotification",
    props: ['payload'],
    setup(props) {
        const data = ref(props.payload);

        return {
            data
        }
    }
}
</script>

<style scoped>

</style>
