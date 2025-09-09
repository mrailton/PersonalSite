<script setup lang="ts">
import type {TableColumn} from '@nuxt/ui'
import {h, resolveComponent} from 'vue'
import type {Article} from "~/types.ts";

const api = useApi()
const router = useRouter()
const toast = useToast()

const articles = ref<Article[]>(await api.get('/articles'))
const isDeleteModalOpen = ref(false)
const articleToDelete = ref<Article | null>(null)
const isDeleting = ref(false)

const openDeleteModal = (article: Article) => {
  articleToDelete.value = article
  isDeleteModalOpen.value = true
}

const closeDeleteModal = () => {
  isDeleteModalOpen.value = false
  articleToDelete.value = null
}

const deleteArticle = async () => {
  if (!articleToDelete.value) return
  
  isDeleting.value = true
  try {
    await api.delete(`/articles/${articleToDelete.value.id}`)
    
    // Remove article from local list
    articles.value = articles.value.filter(a => a.id !== articleToDelete.value!.id)
    
    toast.add({
      title: 'Success',
      description: 'Article deleted successfully',
      color: 'success'
    })
    
    closeDeleteModal()
  } catch (error) {
    console.error('Error deleting article:', error)
    toast.add({
      title: 'Error',
      description: 'Failed to delete article. Please try again.',
      color: 'error'
    })
  } finally {
    isDeleting.value = false
  }
}

const editArticle = (article: Article) => {
  router.push(`/articles/${article.slug}`)
}

const formatPublishedAt = (date: string | null) => {
  if (!date) return 'Not Published'
  return new Date(date).toLocaleDateString('en-IE', {
    day: 'numeric',
    month: 'numeric',
    year: 'numeric'
  })
}

const columns: TableColumn<Article>[] = [
  {accessorKey: 'title', header: 'Title'},
  {accessorKey: 'slug', header: 'Slug'},
  {
    accessorKey: 'published_at',
    header: 'Published At',
    cell: ({row}) => formatPublishedAt(row.original.published_at)
  },
  {
    header: 'Actions',
    cell: ({row}) => h('div', { class: 'flex items-center space-x-2' }, [
      h(resolveComponent('UButton'), {
        size: 'xs',
        variant: 'outline',
        icon: 'i-heroicons-pencil',
        onClick: () => editArticle(row.original)
      }, () => 'Edit'),
      h(resolveComponent('UButton'), {
        size: 'xs',
        color: 'red',
        variant: 'outline',
        icon: 'i-heroicons-trash',
        onClick: () => openDeleteModal(row.original)
      }, () => 'Delete')
    ])
  }
]

</script>

<template>
  <div class="p-6">
    <div class="flex items-center justify-between mb-6">
      <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Articles</h1>
        <p class="text-gray-600 dark:text-gray-400">Manage your published articles</p>
      </div>
      <UButton
        to="/articles/new"
        icon="i-heroicons-plus"
      >
        Create Article
      </UButton>
    </div>
    <UTable :data="articles" :columns="columns"/>

    <!-- Delete Confirmation Modal -->
    <div
      v-if="isDeleteModalOpen"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
      @click="closeDeleteModal"
    >
      <div
        class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4"
        @click.stop
      >
        <!-- Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
            Delete Article
          </h3>
          <button
            @click="closeDeleteModal"
            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
          >
            <UIcon name="i-heroicons-x-mark" class="h-5 w-5" />
          </button>
        </div>

        <!-- Body -->
        <div class="p-6">
          <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
            Are you sure you want to delete this article? This action cannot be undone.
          </p>
          <div v-if="articleToDelete" class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
            <h4 class="font-medium text-gray-900 dark:text-white">{{ articleToDelete.title }}</h4>
          </div>
        </div>

        <!-- Footer -->
        <div class="flex justify-end gap-3 p-6 border-t border-gray-200 dark:border-gray-700">
          <UButton
            color="gray"
            variant="soft"
            :disabled="isDeleting"
            @click="closeDeleteModal"
          >
            Cancel
          </UButton>
          <UButton
            color="red"
            :loading="isDeleting"
            :disabled="isDeleting"
            @click="deleteArticle"
          >
            {{ isDeleting ? 'Deleting...' : 'Delete Article' }}
          </UButton>
        </div>
      </div>
    </div>
  </div>
</template>
