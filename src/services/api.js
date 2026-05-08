/**
 * WordPress API Service
 * For connecting Vue.js frontend with WordPress backend
 */

// Configure your WordPress site URL here
const WP_API_URL = import.meta.env.VITE_WP_API_URL || 'http://localhost/vip-flot/index.php?rest_route=/wp-awnings/v1'

/**
 * Fetch products from WordPress
 * @param {string} category - Filter by category slug (empty means all)
 * @returns {Promise<Array>} Products array
 */
export async function fetchProducts(category = '') {
  try {
    const url = category 
      ? `${WP_API_URL}/products/category/${encodeURIComponent(category)}`
      : `${WP_API_URL}/products`
    
    console.log('Fetching products from:', url)
    const response = await fetch(url)
    
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`)
    }
    
    const data = await response.json()
    console.log('Products loaded:', data.length)
    return data
  } catch (error) {
    console.error('Error fetching products:', error)
    return []
  }
}

/**
 * Fetch single product by ID
 * @param {number} id - Product ID
 * @returns {Promise<Object|null>} Product object or null
 */
export async function fetchProduct(id) {
  try {
    const response = await fetch(`${WP_API_URL}/products/${id}`)
    
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`)
    }
    
    return await response.json()
  } catch (error) {
    console.error('Error fetching product:', error)
    return null
  }
}

/**
 * Fetch categories
 * @returns {Promise<Array>} Categories array
 */
export async function fetchCategories() {
  try {
    const response = await fetch(`${WP_API_URL}/product-categories`)
    
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`)
    }
    
    return await response.json()
  } catch (error) {
    console.error('Error fetching categories:', error)
    return []
  }
}

/**
 * Submit lead form
 * @param {Object} data - Form data
 * @returns {Promise<Object>} Response from server
 */
export async function submitLead(data) {
  try {
    const response = await fetch(`${WP_API_URL}/leads`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        name: data.name,
        phone: data.phone,
        message: data.message || '',
        product_id: data.product_id || 0,
        agree: data.agree || false
      })
    })
    
    if (!response.ok) {
      const error = await response.json()
      throw new Error(error.message || 'Failed to submit form')
    }
    
    return await response.json()
  } catch (error) {
    console.error('Error submitting lead:', error)
    throw error
  }
}

// Export API URL for direct use
export { WP_API_URL }

export default {
  fetchProducts,
  fetchProduct,
  fetchCategories,
  submitLead,
  WP_API_URL
}