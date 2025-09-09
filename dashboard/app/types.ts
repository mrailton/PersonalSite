export interface Article {
  id: string
  title: string
  slug: string
  content: string
  published_at: string | null
  created_at: string
  updated_at: string
}