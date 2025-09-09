export interface ApiError {
    message: string
    status?: number
}

interface FetchOptions {
    method?: 'GET' | 'POST' | 'PUT' | 'PATCH' | 'DELETE'
    headers?: Record<string, string>
    body?: string | Record<string, unknown> | FormData | null
    [key: string]: unknown
}

interface FetchError {
    status?: number
    data?: {
        message?: string
    }
    message?: string
}

export const useApi = () => {
    const config = useRuntimeConfig()
    const baseUrl = config.public.apiUrl

    const getAuthHeaders = (): Record<string, string> => {
        const token = useCookie('auth-token')
        return token.value ? {Authorization: `Bearer ${token.value}`} : {}
    }

    const handleUnauthorized = (): void => {
        const token = useCookie('auth-token', {
            secure: true,
            sameSite: 'strict'
        })
        token.value = null

        navigateTo('/login')
    }

    const request = async <T>(endpoint: string, options: FetchOptions = {}): Promise<T> => {
        const url = `${baseUrl}${endpoint}`

        const requestOptions = {
            ...options,
            headers: {
                'Content-Type': 'application/json',
                ...getAuthHeaders(),
                ...options.headers
            }
        }

        try {
            return await $fetch<T>(url, requestOptions)
        } catch (error) {
            const fetchError = error as FetchError
            
            if (fetchError.status === 401) {
                handleUnauthorized()
                throw {
                    message: 'Session expired. Please login again.',
                    status: 401
                } as ApiError
            }

            throw {
                message: fetchError.data?.message || fetchError.message || 'An error occurred',
                status: fetchError.status
            } as ApiError
        }
    }

    const get = async <T>(endpoint: string, options?: Partial<FetchOptions>): Promise<T> => {
        return request<T>(endpoint, {method: 'GET', ...options})
    }

    const post = async <T>(endpoint: string, body?: string | Record<string, unknown> | FormData | null, options?: Partial<FetchOptions>): Promise<T> => {
        return request<T>(endpoint, {method: 'POST', body, ...options})
    }

    const put = async <T>(endpoint: string, body?: string | Record<string, unknown> | FormData | null, options?: Partial<FetchOptions>): Promise<T> => {
        return request<T>(endpoint, {method: 'PUT', body, ...options})
    }

    const patch = async <T>(endpoint: string, body?: string | Record<string, unknown> | FormData | null, options?: Partial<FetchOptions>): Promise<T> => {
        return request<T>(endpoint, {method: 'PATCH', body, ...options})
    }

    const del = async <T>(endpoint: string, options?: Partial<FetchOptions>): Promise<T> => {
        return request<T>(endpoint, {method: 'DELETE', ...options})
    }

    return {
        get,
        post,
        put,
        patch,
        delete: del
    }
}