import axios from 'https://cdn.jsdelivr.net/npm/axios@1.6.2/dist/esm/axios.min.js';
const { defineComponent, watch, ref, onMounted  } = Vue
export default defineComponent({
    name: 'formUserDetail', // nama child component
    props: {
        roleId: { // nama objek yang akan digunakan parent
            type: Number,
            required: true
        },
        userId: {
            type: Number,
            required: true
        }
    },
    setup(props) {
        const userDetail = ref({
            address:'',
            first_name: '',
            last_name: '',
            phone_number: '',
            place_of_birth: '',
            day_of_birth: '',
            user_id: '',
        })

        const listAdditionRole = ref([]);

        watch(
            () => [props.roleId, props.userId],
            async ([newRoleId, newUserId]) => {
                userDetail.roleId = newRoleId;
                userDetail.user_id = newUserId;
                await getAdditionRoles(newRoleId);
            }
        );

        const getAdditionRoles = async (roleId)=> {
           try {
                let result = await axios.get('addition-role/'+ roleId)
                listAdditionRole.value = result.data.data
            } catch (error) {
                console.log(error)
            }
        }

        function storeDataUserGeneral() {

        }

        return {
            storeDataUserGeneral, getAdditionRoles, listAdditionRole
        }
    },
    template: `
    <div class="relative p-4 w-full max-w-3xl max-h-full">
        <div class="relative bg-gray-300 rounded-lg shadow-sm dark:bg-gray-900">
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white dark:semibold">
                    Form Data User Detail
                </h3>
            </div>
            <div class="p-4 md:p-5 space-y-4">
                <form @submit.prevent="storeDataUserGeneral" id="formStoreUser" class="space-y-4">
                    <div class="grid gap-2 mb-4 grid-cols-2">
                        <div class="col-span-2 sm:col-span-1">
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Nama User  </label>
                            <input
                                type="text"
                                id="name"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-gray-900 dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="Type username"
                            >
                        </div>

                        <div class="col-span-2 sm:col-span-1">
                            <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email Address</label>
                            <input
                                type="email"
                                id="email"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-gray-900 dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="example@example.com"
                            >
                        </div>

                        <div class="col-span-2 sm:col-span-1">
                            <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                            <input
                                type="password"
                                id="name"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-gray-900 dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="Password"
                                autocomplete="new-password"
                            >
                        </div>

                        <div class="col-span-2 sm:col-span-1">
                            <label for="role_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jabatan Tambahan/ addition Role</label>
                            <select class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:gray-600 dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option value="">Select role</option>
                                <option v-for="role in listAdditionRole" :key="role.id" :value="role.id">{{role.name}}</option>
                            </select>
                        </div>

                        <div class="flex gap-4">
                            <button type="submit" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                Simpan
                            </button>
                            <button
                                type="button"
                                class="text-white inline-flex items-center bg-gray-700 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                            cancel
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    `
})
