<script setup lang="ts">
const { apiUrl } = useApi()
const route = useRoute()
const slug = route.params.slug as string

interface Article {
  title: string
  content: string
  published_at: string
}

const { data: article } = await useAsyncData<Article>('article', () =>
    $fetch(`${apiUrl}/articles/${slug}`)
)
</script>

<template>
  <div class="prose lg:prose-xl max-w-4xl mx-auto">
    <h1 class="text-3xl font-semibold text-gray-900 dark:text-white mb-4">
      {{ article?.title }}
    </h1>
    <span class="block text-gray-600 dark:text-gray-300 font-light text-sm mb-8">
      Posted: <FormattedDate :date="article?.published_at" />
    </span>

    <MDC :value="article.content" tag="article" />
  </div>
</template>

<style scoped>

</style>
