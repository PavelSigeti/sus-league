<template>
    <ul class="menu menu-admin">
        <li class="menu-item"><router-link :to="{name: 'tournament.index'}"><span>Серии</span></router-link></li>
        <li class="menu-item"><router-link :to="{name: 'admin.docs'}"><span>Документы</span></router-link></li>
        <li class="menu-item"><router-link :to="{name: 'admin.pages'}"><span>Страницы</span></router-link></li>
        <li class="menu-item"><router-link :to="{name: 'admin.users'}"><span>Пользователи</span></router-link></li>
        <li class="menu-item"><router-link :to="{name: 'admin.settings'}"><span>Настройки сайта</span></router-link></li>
    </ul>
</template>
<script setup lang="ts">
</script>
