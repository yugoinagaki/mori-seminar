export const useApi = () => {
  const config = useRuntimeConfig()
  const base = config.public.apiBase as string

  const get = <T>(path: string, params?: Record<string, string | number>) =>
    useFetch<T>(`${base}${path}`, { params, key: `${path}-${JSON.stringify(params)}` })

  const getOnce = <T>(path: string, params?: Record<string, string | number>) =>
    $fetch<T>(`${base}${path}`, { params })

  return { get, getOnce, base }
}

export const useStorageUrl = (path: string | null | undefined) => {
  const config = useRuntimeConfig()
  if (!path) return null
  if (path.startsWith('http')) return path
  return `${config.public.storageBase}/${path}`
}

export type Post = {
  id: number
  title: string
  slug: string
  content: string | null
  excerpt: string | null
  type: 'news' | 'blog' | 'activity' | 'admission'
  author: { id: number; name: string }
  categories: { id: number; name: string; slug: string }[]
  tags: { id: number; name: string; slug: string }[]
  thumbnail_url: string | null
  status: 'draft' | 'published'
  published_at: string | null
  created_at: string
  updated_at: string
}

export type Member = {
  id: number
  name: string
  name_kana: string | null
  generation: number | null
  university_year: number | null
  major: string | null
  bio: string | null
  profile_image_url: string | null
  status: 'active' | 'ob_og'
  graduated_year: number | null
  twitter_url: string | null
  instagram_url: string | null
  order_index: number
}

export type Paginated<T> = {
  data: T[]
  current_page: number
  last_page: number
  per_page: number
  total: number
}
