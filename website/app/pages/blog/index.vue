<script setup lang="ts">
const { apiUrl } = useApi()

const { data, pending, error } = await useAsyncData('articles', () =>
  $fetch(`${apiUrl}/articles`)
)
</script>

<template>
  <section class="bg-white dark:bg-gray-900">
    <div class="max-w-5xl px-6 mx-auto text-center">
      <h1 class="text-3xl font-semibold text-gray-800 dark:text-gray-100 mb-8">Blog Articles</h1>
      <div v-if="pending" class="text-gray-500 mt-6">Loading articles...</div>
      <div v-else-if="error" class="text-red-600 mt-6">Failed to load articles.</div>
      <div v-else>
        <div v-if="!data || !data.length" class="text-gray-500 mt-6">No articles available.</div>
        <div v-else>
          <div v-for="article in data" :key="article.id" class="flex flex-col items-center justify-center mt-6">
            <ArticleCard :article="article" />
          </div>
        </div>
      </div>

      <div class="flex items-center justify-center mt-6">
      </div>
    </div>
  </section>
</template>

<style scoped>

</style>