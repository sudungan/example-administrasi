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
        const errors = reactive({})
        onMounted(()=> {
            const el = $('#select-multiple')

            el.select2({ width: '100%', placeholder: "Select Student Name",  allowClear: true})

            // Set nilai awal
            el.val(props.modelValue).trigger('change')

            el.on('change', () => {
                emit('update:modelValue', el.val() || [])
            })
        });
        return {
             props
        }
    },
    template: `
    <select
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
