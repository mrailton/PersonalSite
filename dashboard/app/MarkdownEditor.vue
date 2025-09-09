<script setup lang="ts">
import { ref } from 'vue'

const props = defineProps({
  modelValue: {
    type: String,
    default: ''
  },
  label: {
    type: String,
    default: 'Content'
  }
})

const emit = defineEmits(['update:modelValue'])

const isPreviewMode = ref(false)

function updateContent(value: string) {
  emit('update:modelValue', value)
}
</script>

<template>
  <div>
    <!-- Label -->
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
      {{ label }}
    </label>

    <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
      <!-- Header -->
      <div
          class="bg-gray-50 dark:bg-gray-800 px-4 py-2 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between"
      >
        <div class="flex items-center space-x-4">
          <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ label }}</span>
          <div class="text-xs text-gray-500 dark:text-gray-400">
            Markdown supported
          </div>
        </div>

        <UButton
            :variant="isPreviewMode ? 'solid' : 'outline'"
            size="xs"
            type="button"
            @click="isPreviewMode = !isPreviewMode"
        >
          {{ isPreviewMode ? 'Edit' : 'Preview' }}
        </UButton>
      </div>

      <!-- Editor / Preview -->
      <div class="relative">
        <!-- Editor -->
        <UTextarea
            v-if="!isPreviewMode"
            :model-value="props.modelValue"
            @update:model-value="updateContent"
            :rows="20"
            placeholder="Write your article content in markdown..."
            class="border-0 resize-none focus:ring-0 w-full"
            :ui="{
            base: 'font-mono text-sm',
            rounded: 'rounded-none'
          }"
        />

        <!-- Preview -->
        <div
            v-else
            class="p-4 min-h-[500px] prose prose-gray dark:prose-invert max-w-none"
        >
          <MDC :value="props.modelValue" />
        </div>
      </div>
    </div>
  </div>
</template>