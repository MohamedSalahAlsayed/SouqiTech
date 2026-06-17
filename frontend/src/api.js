export const API_BASE_PATH = import.meta.env.VITE_API_BASE_URL || '/api'

export function apiUrl(path) {
  if (!path) return API_BASE_PATH
  if (path.startsWith('http://') || path.startsWith('https://')) {
    return path
  }
  if (path.startsWith('/')) {
    return `${API_BASE_PATH}${path}`
  }
  return `${API_BASE_PATH}/${path}`
}

export async function apiFetch(path, options = {}) {
  return fetch(apiUrl(path), options)
}
