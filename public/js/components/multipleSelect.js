const { defineComponent, watch, ref, onMounted, reactive } = Vue
export default defineComponent({
    name: 'multipleSelect', // nama child component
    emits: ['update:modelValue'],
    props: {
        modelValue: {
            type: Array,
            default: () => []
        },
        options: {
            type: Array,
            required: true
        },
        errors: {
            type: Object,
            default: () => ({})
        }
    },
      setup(props, {emit}) {
        const selected = ref(null)
        const errors = reactive({})

        onMounted(() => {
            const el = $(selected.value)

            el.select2({ width: '100%', placeholder: "Pilih Nama Siswa", allowClear: true })

            // Set nilai awal hanya sekali saat mount
            el.val(props.modelValue.map(String)).trigger('change')

            el.on('change', () => {
                const selectedVal = el.val() || []
                emit('update:modelValue', selectedVal.map(Number))
            })
        })

        watch(() => props.modelValue, (newVal) => {
            const el = $(selected.value)
            const currentVal = el.val() || []
            const newValStr = newVal.map(String)

            if (JSON.stringify(currentVal) !== JSON.stringify(newValStr)) {
                el.val(newValStr).trigger('change')
            }
        })
        return {
             selected
        }
    },
    template: `
    <select
        ref="selected"
        id="select-multiple"
        multiple="multiple"
        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:gray-600 dark:focus:ring-primary-500 dark:focus:border-primary-500"
    >
       <option v-for="option in options" :value="option.id" :key="option.id">
            {{ option.name }}
        </option>
    </select>
    <p v-if="errors.student_ids" class="mt-1 text-sm text-red-600 dark:text-red-500">{{ errors.student_ids }}</p>
    `
})
