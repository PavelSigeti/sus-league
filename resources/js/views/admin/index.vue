<template>
    <h1>Admin content</h1>
    <pre>{{users}}</pre>
</template>

<script>
import {onMounted, ref} from "vue";
import axios from "axios";

export default {
    name: "Admin",
    setup() {
        const users = ref({});

        onMounted(async () => {
            try {
                const {data} = await axios.get('/api/admin');
                users.value = data;
            } catch (e) {
                console.log(e.message);
            }
        });

        return {
            users,
        }
    }
}
</script>

<style scoped>

</style>
