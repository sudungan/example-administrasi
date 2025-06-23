const { defineComponent, ref } = Vue
export default defineComponent({
    name: 'inputPassword', // nama child component
    props: {
        modelValue: { // nama properti yang akan digunakan child saja
            type: String,
            required: true
        },
        error: {
            type: String,
            default: ''
        }
    },
    emits: ['update:modelValue'],
    setup(props,{emit}) {
        const showPassword = ref(false);
        const togglePassword = () => {
            showPassword.value = ! showPassword.value
        }

        const updatePassword = (event)=> {
            emit('update:modelValue', event.target.value)
        }
        return {
            showPassword,
            togglePassword,
            updatePassword,
        }
    },
    template:  `
    <div class="relative">
        <input
        :type="showPassword ? 'text' : 'password'"
        :value="modelValue"
        @input="updatePassword"
        id="name"
        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-gray-900 dark:focus:ring-primary-500 dark:focus:border-primary-500"
        placeholder="Password"
        autocomplete="new-password"
    >
    <p v-if="error" class="absolute text-sm text-red-600 dark:text-red-500 mt-1 mb-2">{{ error }}</p>
        <span
            class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer text-gray-500 dark:text-gray-300"
            @click="togglePassword"
        >
            <template v-if="showPassword">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-3 text-gray-600 dark:text-gray-900">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                </svg>
            </template>
            <template v-else>
               <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-3 text-red-600 dark:text-red-950">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>
            </template>
        </span>
    </div>

    `
})
