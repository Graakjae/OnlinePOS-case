<script setup lang="ts">
import { ref, watch, nextTick, onMounted, onUnmounted, computed } from 'vue'
import axios from 'axios'

const props = defineProps<{
  isOpen: boolean
  editProduct?: {
    id: number
    name: string
    description: string
    price: number
    tax: number
    tagName: string
    tagColor: string
    order: number
  }
}>()

const emit = defineEmits<{
  (e: 'close'): void
  (e: 'product-updated'): void
}>()

const visible = computed({
  get: () => props.isOpen,
  set: (value) => emit('close'),
})

const name = ref('')
const description = ref('')
const price = ref(0)
const tax = ref(0)
const tagName = ref('')
const tagColor = ref('')
const errors = ref({
  name: '',
  price: '',
  tax: '',
  tagColor: '',
})
const showError = ref(false)
const errorMessage = ref('')
const showDeleteDialog = ref(false)

const nameInput = ref()

const tagColors = [
  { value: 'primary', label: 'Primary' },
  { value: 'secondary', label: 'Secondary' },
  { value: 'success', label: 'Success' },
  { value: 'info', label: 'Info' },
  { value: 'warn', label: 'Warn' },
  { value: 'danger', label: 'Danger' },
  { value: 'contrast', label: 'Contrast' },
]

const selectTagColor = (color: string) => {
  tagColor.value = tagColor.value === color ? '' : color
}

const validateForm = () => {
  errors.value = {
    name: '',
    price: '',
    tax: '',
    tagColor: '',
  }
  showError.value = false
  errorMessage.value = ''

  let isValid = true

  if (!name.value?.trim()) {
    errors.value.name = 'Navn er påkrævet'
    isValid = false
  }

  if (!price.value || price.value <= 0) {
    errors.value.price = 'Pris er påkrævet'
    isValid = false
  }

  if (!tax.value || tax.value <= 0) {
    errors.value.tax = 'Momssats er påkrævet'
    isValid = false
  } else if (tax.value > 100) {
    errors.value.tax = 'Momssats kan ikke være over 100%'
    isValid = false
  }

  if (tagName.value && tagName.value.trim() && !tagColor.value) {
    errors.value.tagColor = 'Vælg en tagfarve'
    isValid = false
  }

  if (!isValid) {
    showError.value = true
    errorMessage.value = 'Venligst udfyld alle påkrævede felter korrekt'
  }

  return isValid
}

// Reset form when modal opens/closes or editProduct changes
watch(
  () => props.isOpen,
  (newVal) => {
    if (newVal && props?.editProduct) {
      name.value = props?.editProduct.name
      description.value = props?.editProduct.description
      price.value = props?.editProduct.price
      tax.value = props?.editProduct.tax
      tagName.value = props?.editProduct.tagName
      tagColor.value = props?.editProduct.tagColor
    } else {
      resetForm()
    }
    // Reset error messages when modal opens or closes
    errors.value = {
      name: '',
      price: '',
      tax: '',
      tagColor: '',
    }
    showError.value = false
    errorMessage.value = ''
  },
)

const resetForm = () => {
  name.value = ''
  description.value = ''
  price.value = 0
  tax.value = 0
  tagName.value = ''
  tagColor.value = ''
}

const API_URL = import.meta.env.VITE_API_URL || 'http://127.0.0.1:8000'

const addProduct = async () => {
  if (!validateForm()) return

  try {
    if (props?.editProduct) {
      await axios.put(`${API_URL}/api/products/${props?.editProduct.id}`, {
        name: name.value,
        description: description.value,
        price: price.value,
        tax: tax.value,
        tagName: tagName.value,
        tagColor: tagColor.value,
      })
    } else {
      await axios.post(`${API_URL}/api/products`, {
        name: name.value,
        description: description.value,
        price: price.value,
        tax: tax.value,
        tagName: tagName.value,
        tagColor: tagColor.value,
      })
    }
    resetForm()
    emit('product-updated')
    emit('close')
  } catch (error) {
    showError.value = true
    errorMessage.value = props?.editProduct
      ? 'Der opstod en fejl ved opdatering af produktet'
      : 'Der opstod en fejl ved oprettelse af produktet'
  }
}

const deleteProduct = async () => {
  try {
    await axios.delete(`${API_URL}/api/products/${props?.editProduct?.id}`)
    resetForm()
    emit('product-updated')
    emit('close')
    showDeleteDialog.value = false
  } catch (error) {
    showError.value = true
    errorMessage.value = 'Der opstod en fejl ved sletning af produktet'
  }
}

const confirmDelete = () => {
  showDeleteDialog.value = true
}

const cancelDelete = () => {
  showDeleteDialog.value = false
}

const isMobile = computed(() => window.innerWidth < 768)
const dialogStyle = computed(() => ({
  maxHeight: '100vh',
  borderRadius: isMobile.value ? '0px' : '10px',
}))
</script>

<template>
  <Dialog
    :visible="isOpen"
    @update:visible="(value) => emit('close')"
    modal
    class="w-full md:max-w-[450px] h-screen md:h-auto"
    :header="editProduct ? 'Rediger produkt' : 'Opret produkt'"
    :closable="true"
    :contentClass="'h-full flex flex-col'"
    :style="dialogStyle"
  >
    <Message v-if="showError" severity="error" class="my-4">
      {{ errorMessage }}
    </Message>

    <div class="flex-1 space-y-4 mb-10">
      <div>
        <label class="block mb-[5px]">Navn <span class="text-red-500">*</span></label>
        <InputText
          ref="nameInput"
          v-model="name"
          :class="{ 'p-invalid': errors.name }"
          class="w-full"
          autofocus
        />
        <small v-if="errors.name" class="text-red-500">{{ errors.name }}</small>
      </div>
      <div>
        <label class="block mb-[5px]">Beskrivelse</label>
        <Textarea v-model="description" class="w-full h-[100px]" />
      </div>
      <div>
        <label class="block mb-[5px]">Pris <span class="text-red-500">*</span></label>
        <InputNumber
          v-model="price"
          :class="{ 'p-invalid': errors.price }"
          class="w-full"
          :min="0"
          :maxFractionDigits="2"
          mode="currency"
          currency="DKK"
          locale="da-DK"
          :inputClass="{ 'p-invalid': errors.price }"
        />
        <small v-if="errors.price" class="text-red-500">{{ errors.price }}</small>
      </div>
      <div>
        <label class="block mb-[5px]">Momssats <span class="text-red-500">*</span></label>
        <InputNumber
          v-model="tax"
          :class="{ 'p-invalid': errors.tax }"
          class="w-full"
          :min="0"
          suffix="%"
          :inputClass="{ 'p-invalid': errors.tax }"
        />
        <small v-if="errors.tax" class="text-red-500">{{ errors.tax }}</small>
      </div>
      <div>
        <label class="block mb-[5px]">Tagnavn (max 20 tegn)</label>
        <InputText v-model="tagName" class="w-full" :maxlength="20" />
      </div>
      <div>
        <label class="block mb-[5px]">Tagfarve</label>
        <div class="flex flex-wrap gap-2 h-[80px] overflow-y-auto p-1">
          <Tag
            v-for="color in tagColors"
            :key="color.value"
            :value="tagName || 'Vælg tagfarve'"
            :severity="color.value"
            class="cursor-pointer max-h-[30px]"
            :class="{ 'ring-2 ring-green-500': tagColor === color.value }"
            @click="selectTagColor(color.value)"
          />
        </div>
        <small v-if="errors.tagColor" class="text-red-500">{{ errors.tagColor }}</small>
      </div>
    </div>

    <div class="flex justify-between gap-2 mb-4">
      <Button
        v-if="editProduct"
        type="button"
        label="Slet"
        severity="danger"
        @click="confirmDelete"
      >
        Slet
      </Button>
      <div class="flex gap-2 ml-auto">
        <Button type="button" label="Annuller" severity="secondary" @click="emit('close')">
          Annuller
        </Button>
        <Button
          type="button"
          :label="editProduct ? 'Gem' : 'Opret'"
          severity="primary"
          @click="addProduct"
        >
          {{ editProduct ? 'Gem' : 'Opret' }}
        </Button>
      </div>
    </div>
  </Dialog>

  <Dialog
    v-model:visible="showDeleteDialog"
    modal
    class="w-full md:max-w-[400px]"
    header="Bekræft sletning"
    :closable="true"
  >
    <div class="flex items-center gap-2 mb-4">
      <i class="pi pi-exclamation-triangle text-yellow-500 text-xl"></i>
      <span>Er du sikker på at du vil slette dette produkt?</span>
    </div>
    <div class="flex justify-end gap-2">
      <Button label="Nej" icon="pi pi-times" @click="cancelDelete" text />
      <Button label="Ja" icon="pi pi-check" @click="deleteProduct" severity="danger" />
    </div>
  </Dialog>
</template>
