<script setup lang="ts">
import type {Article} from "~/types";
import MarkdownEditor from "~/MarkdownEditor.vue";

const route = useRoute()
const router = useRouter()
const api = useApi()
const slug = route.params.slug as string

const article = ref<Article>(await api.get(`/articles/${slug}`))
const isSaving = ref(false)

const formData = ref({
  title: article.value.title,
  slug: article.value.slug,
  content: article.value.content,
  published_at: article.value.published_at ? new Date(article.value.published_at).toISOString().slice(0, 16) : ''
})

const toast = useToast()

const saveArticle = async () => {
  isSaving.value = true
  try {
    const payload = {
      ...formData.value,
      published_at: formData.value.published_at ? new Date(formData.value.published_at).toISOString() : null
    }

    await api.put(`/articles/${article.value.id}`, payload)

    article.value = {...article.value, ...payload}

    toast.add({
      title: 'Success',
      description: 'Article saved successfully',
      color: 'success'
    })

    await router.push('/articles')
  } catch (error) {
    console.error('Error saving article:', error)
    toast.add({
      title: 'Error',
      description: 'Failed to save article. Please try again.',
      color: 'error'
    })
  } finally {
    isSaving.value = false
  }
}
</script>

<template>
  <div class="p-6 max-w-6xl mx-auto">
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Edit Article</h1>
      <p class="text-gray-600 dark:text-gray-400">Make changes to your article and save when ready</p>
    </div>

    <form class="space-y-6" @submit.prevent="saveArticle">
      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
          Title <span class="text-red-500">*</span>
        </label>
        <UInput
            v-model="formData.title"
            placeholder="Article title..."
            size="lg"
            class="w-full"
        />
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
          Slug <span class="text-red-500">*</span>
        </label>
        <UInput
            v-model="formData.slug"
            placeholder="article-slug"
            class="w-full"
        />
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
          Published At
        </label>
        <UInput
            v-model="formData.published_at"
            type="datetime-local"
            placeholder="Select publish date and time..."
            class="w-full"
        />
      </div>

      <MarkdownEditor v-model="formData.content" label="Article Content" />
    </form>
    <!-- Actions -->
    <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-700">
      <UButton
          type="button"
          variant="outline"
          @click="$router.push('/articles')"
      >
        Cancel
      </UButton>

      <UButton
          type="submit"
          :loading="isSaving"
          :disabled="isSaving"
          @click="saveArticle"
      >
        {{ isSaving ? 'Saving...' : 'Save Article' }}
      </UButton>
    </div>
  </div>
</template>

