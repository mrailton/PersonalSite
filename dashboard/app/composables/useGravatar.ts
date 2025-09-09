import md5 from 'js-md5'

export const useGravatar = () => {
  const getGravatarUrl = (email: string, size: number = 80): string => {
    const trimmedEmail = email.trim().toLowerCase()
    const hash = md5(trimmedEmail)
    return `https://www.gravatar.com/avatar/${hash}?s=${size}&d=identicon`
  }

  return {
    getGravatarUrl
  }
}