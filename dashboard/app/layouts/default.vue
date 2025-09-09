<script setup lang="ts">
const colorMode = useColorMode()
const route = useRoute()
const { user, fetchUser } = useUser()
const { getGravatarUrl } = useGravatar()

const isOpen = ref(true)

const gravatarUrl = computed(() => {
  if (!user.value?.email) return ''
  return getGravatarUrl(user.value.email, 40)
})

const isLogoutModalOpen = ref(false)
const isUserMenuOpen = ref(false)

const toggleUserMenu = () => {
  console.log('toggleUserMenu called')
  isUserMenuOpen.value = !isUserMenuOpen.value
}

const confirmLogout = () => {
  console.log('confirmLogout called')
  isUserMenuOpen.value = false
  isLogoutModalOpen.value = true
}

const logout = async () => {
  try {
    const token = useCookie('auth-token', {
      secure: true,
      sameSite: 'strict'
    })
    token.value = null

    isLogoutModalOpen.value = false
    await navigateTo('/login')
  } catch (error) {
    console.error('Logout error:', error)
    isLogoutModalOpen.value = false
  }
}

const cancelLogout = () => {
  isLogoutModalOpen.value = false
}

// Close user menu when clicking outside
onMounted(() => {
  fetchUser()

  document.addEventListener('click', (e) => {
    const target = e.target as Element
    if (!target.closest('.user-menu-container')) {
      isUserMenuOpen.value = false
    }
  })
})

const links = [{
  label: 'Dashboard',
  icon: 'i-heroicons-home',
  to: '/'
}, {
  label: 'Articles',
  icon: 'i-heroicons-document-text',
  to: '/articles'
}]
</script>

<template>
  <div>
    <div class="flex h-screen bg-gray-50 dark:bg-gray-900">
    <div
      class="flex flex-col bg-white dark:bg-gray-800 shadow-lg transition-all duration-300 ease-in-out"
      :class="isOpen ? 'w-64' : 'w-16'"
    >
      <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
        <h1 v-if="isOpen" class="text-lg font-semibold text-gray-900 dark:text-white">
          Dashboard
        </h1>
        <UButton
          :icon="isOpen ? 'i-heroicons-x-mark' : 'i-heroicons-bars-3'"
          size="xs"
          color="gray"
          variant="ghost"
          @click="isOpen = !isOpen"
        />
      </div>

      <nav class="flex-1 overflow-y-auto p-2">
        <ul class="space-y-1">
          <li v-for="link in links" :key="link.to">
            <NuxtLink
              :to="link.to"
              class="flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors hover:bg-gray-100 dark:hover:bg-gray-700"
              :class="(link.to === '/' ? route.path === link.to : route.path.startsWith(link.to))
                ? 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white' 
                : 'text-gray-600 dark:text-gray-300'"
            >
              <UIcon :name="link.icon" class="mr-3 h-4 w-4" />
              <span v-if="isOpen">{{ link.label }}</span>
            </NuxtLink>
          </li>
        </ul>
      </nav>

      <div class="p-4 border-t border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between">
          <div v-if="isOpen" class="flex items-center space-x-3 flex-1 relative user-menu-container">
            <div
              class="flex items-center space-x-3 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md p-2 -m-2 transition-colors w-full"
              @click="toggleUserMenu"
            >
              <UAvatar
                :src="gravatarUrl"
                size="sm"
                :alt="user?.name || 'User'"
              />
              <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                  {{ user?.name || 'Loading...' }}
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                  {{ user?.email || '' }}
                </p>
              </div>
              <UIcon name="i-heroicons-chevron-up-down" class="h-4 w-4 text-gray-400" />
            </div>

            <!-- Custom Dropdown Menu -->
            <div
              v-if="isUserMenuOpen"
              class="absolute bottom-full left-0 mb-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg border border-gray-200 dark:border-gray-700 z-50"
            >
              <div class="py-1">
                <button
                  class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center"
                  @click="confirmLogout"
                >
                  <UIcon name="i-heroicons-arrow-right-on-rectangle" class="mr-3 h-4 w-4" />
                  Logout
                </button>
              </div>
            </div>
          </div>

          <div v-else class="relative user-menu-container">
            <UAvatar
              :src="gravatarUrl"
              size="sm"
              :alt="user?.name || 'User'"
              class="cursor-pointer"
              @click="toggleUserMenu"
            />

            <!-- Custom Dropdown Menu for collapsed state -->
            <div
              v-if="isUserMenuOpen"
              class="absolute bottom-full left-0 mb-2 w-32 bg-white dark:bg-gray-800 rounded-md shadow-lg border border-gray-200 dark:border-gray-700 z-50"
            >
              <div class="py-1">
                <button
                  class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center"
                  @click="confirmLogout"
                >
                  <UIcon name="i-heroicons-arrow-right-on-rectangle" class="mr-3 h-4 w-4" />
                  Logout
                </button>
              </div>
            </div>
          </div>
          <ClientOnly>
            <UButton
              :icon="colorMode.value === 'dark' ? 'i-heroicons-sun' : 'i-heroicons-moon'"
              size="xs"
              color="gray"
              variant="ghost"
              @click="colorMode.preference = colorMode.value === 'dark' ? 'light' : 'dark'"
            />
          </ClientOnly>
        </div>
      </div>
    </div>

    <div class="flex-1 flex flex-col overflow-hidden">
      <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 dark:bg-gray-900 p-6">
        <slot />
      </main>
    </div>

  </div>

  <!-- Logout Confirmation Modal -->
  <div
    v-if="isLogoutModalOpen"
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    @click="cancelLogout"
  >
    <div
      class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4"
      @click.stop
    >
      <!-- Header -->
      <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
          Confirm Logout
        </h3>
        <button
          @click="cancelLogout"
          class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
        >
          <UIcon name="i-heroicons-x-mark" class="h-5 w-5" />
        </button>
      </div>

      <!-- Body -->
      <div class="p-6">
        <p class="text-sm text-gray-500 dark:text-gray-400">
          Are you sure you want to logout? You will be redirected to the login page.
        </p>
      </div>

      <!-- Footer -->
      <div class="flex justify-end gap-3 p-6 border-t border-gray-200 dark:border-gray-700">
        <UButton
          color="gray"
          variant="soft"
          @click="cancelLogout"
        >
          Cancel
        </UButton>
        <UButton
          color="red"
          @click="logout"
        >
          Logout
        </UButton>
      </div>
    </div>
  </div>
  </div>
</template>