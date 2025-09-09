export const useApi = () => {
  const config = useRuntimeConfig()
  
  // Use server-side URL for SSR, client-side URL for hydration/navigation
  const apiUrl = import.meta.server ? config.apiUrl : config.public.apiUrl
  
  return {
    apiUrl
  }
}