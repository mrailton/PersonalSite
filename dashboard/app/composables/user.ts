export interface User {
    id: string
    email: string
    name: string
}

export const useUser = () => {
    const user = ref<User | null>(null)
    const loading = ref(false)
    const error = ref<string | null>(null)

    const api = useApi()

    const fetchUser = async () => {
        loading.value = true
        error.value = null

        try {
            user.value = await api.get<User>('/auth/me')
        } catch (err: any) {
            error.value = err.message || 'Failed to fetch user data'
            user.value = null
        } finally {
            loading.value = false
        }
    }

    return {
        user: readonly(user),
        loading: readonly(loading),
        error: readonly(error),
        fetchUser
    }
}