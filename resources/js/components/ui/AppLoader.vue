<template>
    <div :class="['loading-overlay', { 'section-loader': isSectionLoader }]">
        <img src="@/static/loader.svg" alt="loader">
    </div>
</template>

<script>
export default {
    name: "AppLoader",
    props: {
        isSectionLoader: {
            type: Boolean,
            required: false,
            default: false
        }
    }
}
</script>

<style scoped>
.section-loader {
    border-radius: 10px;
}

img {
    animation: 2s spin linear infinite;
    width: 64px;
    height: 64px;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }
    50% {
        transform: rotate(180deg);
    }
    100% {
        transform: rotate(360deg);
    }
}
</style>
