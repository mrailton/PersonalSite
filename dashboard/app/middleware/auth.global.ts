export default defineNuxtRouteMiddleware((to) => {
  const token = useCookie('auth-token', {
    default: () => null,
    secure: true,
    sameSite: 'strict',
    httpOnly: false
  })
  
  if (!token.value && to.path !== '/login') {
    return navigateTo('/login')
  }
  
  if (token.value && to.path === '/login') {
    return navigateTo('/')
  }
})