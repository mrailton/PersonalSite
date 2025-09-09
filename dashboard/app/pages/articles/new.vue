<script setup lang="ts">
import MarkdownEditor from "~/MarkdownEditor.vue";

const api = useApi()
const router = useRouter()
const toast = useToast()
const isSaving = ref(false)

const formData = ref({
  title: '',
  slug: '',
  content: '',
  published_at: ''
})

const createArticle = async () => {
  isSaving.value = true
  try {
    const payload = {
      ...formData.value,
      published_at: formData.value.published_at ? new Date(formData.value.published_at).toISOString() : null
    }
    
    await api.post('/articles', payload)

    toast.add({
      title: 'Success',
      description: 'Article created successfully',
      color: 'success'
    })

    await router.push('/articles')
  } catch (error) {
    console.error('Error creating article:', error)
    toast.add({
      title: 'Error',
      description: 'Failed to create article. Please try again.',
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
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Create New Article</h1>
      <p class="text-gray-600 dark:text-gray-400">Write and publish a new article</p>
    </div>

    <form class="space-y-6" @submit.prevent="createArticle">
      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
          Title <span class="text-red-500">*</span>
        </label>
        <UInput 
          v-model="formData.title"
          placeholder="Article title..."
          size="lg"
          class="w-full"
          required
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
          required
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
        @click="createArticle"
      >
        {{ isSaving ? 'Creating...' : 'Create Article' }}
      </UButton>
    </div>
  </div>
</template>