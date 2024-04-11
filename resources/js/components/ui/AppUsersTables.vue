<template>
    <table v-if="users" class="result-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Яхтсмен</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(user, i) in users" :key="i">
                <td>{{i+1}}</td>
                <td><div class="result-table__name">
                    {{user.name}} {{user.surname}} <span class="result-table__nick">{{user.nickname}}</span>
                </div></td>
            </tr>
        </tbody>
    </table>
</template>

<script>
export default {
    name: "AppUsersTables",
    props: {
        users: {
            type: Array,
            default: null,
        }
    }
}
</script>

<style scoped>

</style>
