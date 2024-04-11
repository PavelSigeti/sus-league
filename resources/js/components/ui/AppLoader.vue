<template>
    <div class="loading-overlay">
        <img src="@/static/loader.svg" alt="loader">
    </div>
</template>

<script>
export default {
    name: "AppLoader",
}
</script>

<style scoped>
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
