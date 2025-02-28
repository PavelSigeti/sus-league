<template>
    <div class="user-stage">
      <div class="user-stage__header">
        <router-link :to="`/dashboard/stage/${stage.id}`" class="user-stage__title">{{ stage.title }}</router-link>
        <div class="user-stage__tournament">{{ stage.tournament }}</div>
      </div>
      <div v-if="stage.excerpt" class="user-stage__excerpt content" v-html="stage.excerpt"></div>
      <div class="user-stage__date">
        <span>Даты проведения: {{ formatDate(stage.register_start) }}</span>
      </div>
      <router-link :to="`/dashboard/stage/${stage.id}`" class="btn btn-border btn-settings-280 mt10">Подробнее</router-link>
    </div>
  </template>
  
  <script setup>
  import { defineProps } from 'vue';
  
  const props = defineProps({
    stage: {
      type: Object,
      required: true
    }
  });

  const formatDate = (date) => {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(date).toLocaleDateString('ru-RU', options);
  };
  </script>
  
  <style scoped>
  .user-stage {
    margin-bottom: 20px;
  }
  
  .user-stage__header {
    font-size: 18px;
    font-weight: bold;
  }
  
  .user-stage__title {
    text-decoration: none;
    color: #333;
  }
  
  .user-stage__tournament {
    font-size: 14px;
    color: #777;
  }
  
  .user-stage__excerpt {
    margin-top: 10px;
  }
  
  .user-stage__date {
    margin-top: 10px;
  }
  
  .btn-settings-280 {
    margin-top: 10px;
  }
  </style>
  