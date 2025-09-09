<script setup lang="ts">
import type { ApiError } from '~/composables/api'

definePageMeta({
  layout: 'auth'
})

interface LoginResponse {
  access_token: string
}

const api = useApi()
const router = useRouter()
const colorMode = useColorMode()

const form = reactive({
  email: '',
  password: ''
})

const loading = ref(false)
const error = ref('')

const login = async () => {
  if (!form.email || !form.password) {
    error.value = 'Please enter both email and password'
    return
  }

  loading.value = true
  error.value = ''

  try {
    const response = await api.post<LoginResponse>('/auth/login', {
      email: form.email,
      password: form.password
    })

    if (response.access_token) {
      const token = useCookie('auth-token', {
        secure: true,
        sameSite: 'strict'
      })
      token.value = response.access_token
      
      await router.push('/')
    }
  } catch (err) {
    const apiError = err as ApiError
    error.value = apiError.message || 'Login failed. Please try again.'
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900">
    <UCard class="max-w-md w-full">
      <template #header>
        <div class="flex items-center justify-between">
          <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
            Sign in to your account
          </h2>
          <UButton
            :icon="colorMode.value === 'dark' ? 'i-heroicons-sun' : 'i-heroicons-moon'"
            size="xs"
            color="gray"
            variant="ghost"
            @click="colorMode.preference = colorMode.value === 'dark' ? 'light' : 'dark'"
          />
        </div>
      </template>

      <UForm :state="form" class="space-y-4" @submit="login">
        <UFormField label="Email address" name="email" required>
          <UInput
            v-model="form.email"
            type="email"
            placeholder="Enter your email"
            autocomplete="email"
            required
            size="lg"
            class="w-full"
          />
        </UFormField>

        <UFormField label="Password" name="password" required>
          <UInput
            v-model="form.password"
            type="password"
            placeholder="Enter your password"
            autocomplete="current-password"
            required
            size="lg"
            class="w-full"
          />
        </UFormField>

        <UAlert
          v-if="error"
          color="red"
          variant="soft"
          :title="error"
        />

        <UButton
          type="submit"
          :loading="loading"
          block
          size="lg"
        >
          {{ loading ? 'Signing in...' : 'Sign in' }}
        </UButton>
      </UForm>
    </UCard>
  </div>
</template>
