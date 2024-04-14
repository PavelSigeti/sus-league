<template>
  <div class="user">
      <div class="user__container">
          <div class="user-icon">
              <img src="@/static/sea.png" alt="">
          </div>
          <router-link to="/dashboard/settings" class="user-data">
              <div class="user-name">
                  {{user.name}} {{user.surname}}
              </div>
              <div class="user-nick">
                  <div class="user-id">#{{user.id}}</div>
              </div>
          </router-link>
      </div>
      <div class="user-settings">
          <router-link to="/dashboard/settings"><i class="ri-settings-2-fill"></i></router-link>
      </div>
  </div>
</template>

<script setup>
import { useStore } from 'vuex';
import { ref } from 'vue';

const store = useStore();

const user = ref(store.getters['auth/user']);

</script>

<style scoped>

</style>
