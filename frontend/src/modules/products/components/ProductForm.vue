<template>
  <div class="flex flex-col gap-4">
    <FormField
      :label="t('productsForSale.dialogProductProperties.name')"
      required
      :error="getFirstErrorMessage(v$.name)"
    >
      <InputText v-model="v$.name.$model" />
    </FormField>
    <FormField :label="t('productsForSale.dialogProductProperties.description')">
      <Textarea
        v-model="formData.description"
        rows="5"
        autoResize
      />
    </FormField>
    <FormField
      :label="t('productsForSale.dialogProductProperties.price')"
      required
      :error="getFirstErrorMessage(v$.price)"
    >
      <InputNumber
        v-model="v$.price.$model"
        mode="decimal"
        :maxFractionDigits="2"
        :minFractionDigits="2"
      />
    </FormField>
    <FormField
      :label="t('productsForSale.dialogProductProperties.vatRate')"
      :error="getFirstErrorMessage(v$.vatRate)"
      required
    >
      <InputNumber
        v-model="v$.vatRate.$model"
        mode="decimal"
        suffix="%"
        :useGrouping="false"
        :min="0"
        :max="100"
      />
    </FormField>
    <FormField :label="t('productsForSale.dialogProductProperties.tagName')">
      <InputText v-model="formData.tag.name" />
    </FormField>
    <FormField :label="t('productsForSale.dialogProductProperties.tagColor')">
      <Select
        v-model="formData.tag.color"
        :options="tagColorOptions"
        optionLabel="label"
        optionValue="value"
      />
    </FormField>
  </div>
</template>

<script setup lang="ts">
import FormField from '@/app/components/FormField.vue'
import Textarea from 'primevue/textarea'
import Select from 'primevue/select'
import InputText from 'primevue/inputtext'
import InputNumber from 'primevue/inputnumber'
import { useI18n } from 'vue-i18n'
import { TagColor } from '@/types/models/Tag'
import { useValidation } from '@/plugins/validation'
import { useVuelidate } from '@vuelidate/core'
import type { CreateProductPayload, UpdateProductPayload } from '@/types/models/Product'
import { computed, ref, watch } from 'vue'

const { t } = useI18n()
const { required, getFirstErrorMessage } = useValidation()

type ModelPayload = CreateProductPayload | UpdateProductPayload
type FormData = Omit<CreateProductPayload | UpdateProductPayload, 'price' | 'vatRate'> & {
  tag: {
    name: string
    color: TagColor
  }
  price: number | null
  vatRate: number | null
}

const model = defineModel<ModelPayload>()
const formData = ref<FormData>({
  name: '',
  description: '',
  price: null,
  vatRate: null,
  tag: {
    name: '',
    color: TagColor.Red,
  },
  ...model.value,
})

const vatRate = computed({
  get: () => (formData.value.vatRate ? formData.value.vatRate * 100 : null),
  set: (value) => {
    formData.value.vatRate = value ? Number(value) / 100 : null
  },
})
const tagColorOptions = Object.values(TagColor).map((color) => ({
  label: t(`colors.${color}`),
  value: color,
}))

const rules = {
  name: { required, $autoDirty: true },
  price: { required, $autoDirty: true },
  vatRate: { required, $autoDirty: true },
}

const v$ = useVuelidate(rules, formData)

watch(
  formData,
  () => {
    model.value = {
      ...model.value,
      ...formData.value,
      price: formData.value.price ? Number(formData.value.price) : 0,
      vatRate: formData.value.vatRate ? Number(formData.value.vatRate) : 0,
    }
  },
  { deep: true },
)
</script>
