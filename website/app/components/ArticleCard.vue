<script setup lang="ts">
const {article} = defineProps(['article'])
</script>

<template>
  <NuxtLink :to="{ name: 'blog-slug', params: {slug: article.slug}}" class="mb-8 max-w-3xl w-full block bg-white dark:bg-gray-700 shadow-md border-t-4 border-indigo-600">
    <div class="flex items-center justify-between px-4 py-2">
      <h3 class="text-lg font-medium text-gray-700 dark:text-white max-w-[80%] text-left">
        {{ article.title }}
      </h3>

      <span class="block text-gray-600 dark:text-gray-100 font-light text-sm text-right">
        <FormattedDate :date="article?.published_at" />
      </span>
    </div>
  </NuxtLink>
</template>

<style scoped>

</style>