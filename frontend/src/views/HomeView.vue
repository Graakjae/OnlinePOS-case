<script setup lang="ts">
import { ref, onMounted, computed, onUnmounted } from 'vue'
import axios from 'axios'
import ProductsForm from '../components/ProductsForm.vue'
import draggable from 'vuedraggable'

interface Product {
  id: number
  name: string
  description: string
  price: number
  tax: number
  tagName: string
  tagColor: string
  order: number
}

const products = ref<Product[]>([])
const isModalOpen = ref(false)
const editProduct = ref<Product | undefined>(undefined)
const windowWidth = ref(window.innerWidth)

const API_URL = import.meta.env.VITE_API_URL || 'http://127.0.0.1:8000'

const updateWindowWidth = () => {
  windowWidth.value = window.innerWidth
}

onMounted(() => {
  window.addEventListener('resize', updateWindowWidth)
  fetchProducts()
})

onUnmounted(() => {
  window.removeEventListener('resize', updateWindowWidth)
})

const buttonSize = computed(() => {
  return windowWidth.value >= 768 ? 'normal' : 'small'
})

const formatPrice = (price: number) => {
  return new Intl.NumberFormat('da-DK', {
    style: 'currency',
    currency: 'DKK',
  }).format(price)
}

const fetchProducts = async () => {
  try {
    const response = await axios.get(`${API_URL}/api/products`)
    products.value = response.data
  } catch (error) {
    console.error('Error fetching products:', error)
  }
}

const openModal = () => {
  isModalOpen.value = true
  editProduct.value = undefined
}

const closeModal = () => {
  isModalOpen.value = false
  editProduct.value = undefined
}

const editProductModal = (product: Product) => {
  editProduct.value = product
  isModalOpen.value = true
}

const handleProductUpdated = () => {
  fetchProducts()
}

const updateOrder = async (evt: any) => {
  try {
    await axios.post(`${API_URL}/api/products/order`, {
      order: products.value.map((p) => p.id),
    })
  } catch (error) {
    console.error('Error updating order:', error)
  }
}
</script>

<template>
  <div class="p-6 bg-gray-100">
    <h1 class="text-lg md:text-2xl font-medium text-gray-500 mb-4">Salgsprodukter</h1>
    <div class="mb-6 flex justify-end">
      <Button @click="openModal" icon="pi pi-plus" label="Opret produkt" :size="buttonSize" />
    </div>
    <div class="bg-white p-4 rounded-lg border border-gray-200 max-w-screen-2xl mx-auto">
      <h2 class="text-lg md:text-xl font-bold text-gray-600">
        Alle produkter ({{ products.length }})
      </h2>
      <div class="sticky top-0 bg-white z-10">
        <div class="flex border-b border-gray-200">
          <div class="p-4 flex justify-center w-12 h-12"></div>
          <div class="grid grid-cols-12 gap-4 p-4 rounded-lg font-semibold w-full text-gray-600">
            <div class="col-span-1 hidden xl:block">Produkt ID</div>
            <div class="col-span-8 sm:col-span-4 lg:col-span-2">Navn</div>
            <div class="col-span-4 sm:col-span-6 lg:col-span-4 hidden sm:block">Beskrivelse</div>
            <div class="col-span-4 sm:col-span-2 xl:col-span-1">Pris</div>
            <div class="col-span-2 hidden lg:block">Momssats</div>
            <div class="col-span-2 hidden lg:block">Tag</div>
          </div>
          <div class="p-4 flex justify-center w-12 h-12"></div>
        </div>
      </div>
      <div class="mt-4">
        <draggable
          v-model="products"
          item-key="id"
          @change="updateOrder"
          class="space-y-2"
          handle=".drag-handle"
          ghost-class="ghost"
        >
          <template #item="{ element: product }">
            <div class="flex items-center border-b border-gray-200 last:border-b-0">
              <div class="drag-handle cursor-move p-4 flex justify-center">
                <i class="pi pi-bars text-gray-700 text-xl"></i>
              </div>
              <div class="grid grid-cols-12 gap-4 p-4 flex-1 items-center">
                <div class="col-span-1 text-gray-700 hidden xl:block">{{ product.id }}</div>
                <div class="col-span-8 sm:col-span-4 lg:col-span-2 text-gray-700">
                  {{ product.name }}
                </div>
                <div class="col-span-4 sm:col-span-6 lg:col-span-4 text-gray-700 hidden sm:block">
                  {{ product.description || '-' }}
                </div>
                <div class="col-span-4 sm:col-span-2 xl:col-span-1 text-gray-700">
                  {{ formatPrice(product.price) }}
                </div>
                <div class="col-span-2 text-gray-700 hidden lg:block">{{ product.tax }}%</div>
                <div class="col-span-2 hidden lg:block">
                  <Tag
                    v-if="product.tagName"
                    :value="product.tagName"
                    :severity="product.tagColor"
                  />
                </div>
              </div>
              <div
                class="p-4 flex justify-center cursor-pointer"
                @click="editProductModal(product)"
              >
                <img src="@/assets/icons/chevron.png" alt="chevron" class="w-4 h-4" />
              </div>
            </div>
          </template>
        </draggable>
      </div>
    </div>

    <ProductsForm
      :is-open="isModalOpen"
      :edit-product="editProduct"
      @close="closeModal"
      @product-updated="handleProductUpdated"
    />
  </div>
</template>

<style scoped>
.ghost {
  opacity: 0.5;
  background: #c8ebfb;
}

.drag-handle {
  cursor: move;
  user-select: none;
}
</style>
