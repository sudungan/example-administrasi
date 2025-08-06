const { defineComponent, ref, watch, computed } = Vue
export default defineComponent({
    name: 'dataTableSchedule', // nama child component
    // props: {
    //     visableCard: {
    //         type: String,
    //         required: true
    //     }
    // },
    emits: ['addTime'],
    setup(props, {emit}) {
        const settingTime =()=> {
            emit('addTime')
        }
        return {
            settingTime
        }
    },
    template: `

<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-white uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" @click="settingTime" class="text-center underline cursor-pointer hover:font-bold" hover:text-red-900>
                   Time
                </th>
                <th scope="col" class="px-2 py-3">
                    Color
                </th>
                <th scope="col" class="px-6 py-3">
                    Category
                </th>
                <th scope="col" class="px-6 py-3">
                    Price
                </th>
                <th scope="col" class="px-6 py-3">
                    Action
                </th>
            </tr>
        </thead>
        <tbody>
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 border-b">
                <th class="px-2 py-2">
                    07.15 - 07.30
                </th>
                <td class="px-6 py-4">
                    Silver
                </td>
                <td class="px-2 py-4 bg-blue-500">
                    Laptop
                </td>
                <td class="px-6 py-4">
                    $2999
                </td>
                <td class="px-6 py-4 bg-blue-500">
                    <a href="#" class="font-medium text-white hover:underline">Edit</a>
                </td>
            </tr>
            <tr class="bg-blue-600 border-b border-blue-400">
                 <th class="px-2 py-2">
                    07.15 - 07.30
                </th>
                <td class="px-6 py-4">
                    White
                </td>
                <td class="px-6 py-4 bg-blue-500">
                    Laptop PC
                </td>
                <td class="px-6 py-4">
                    $1999
                </td>
                <td class="px-6 py-4 bg-blue-500">
                    <a href="#" class="font-medium text-white hover:underline">Edit</a>
                </td>
            </tr>
            <tr class="bg-blue-600 border-b border-blue-400">
                 <th class="px-2 py-2">
                    07.15 - 07.30
                </th>
                <td class="px-6 py-4">
                    Black
                </td>
                <td class="px-6 py-4 bg-blue-500">
                    Accessories
                </td>
                <td class="px-6 py-4">
                    $99
                </td>
                <td class="px-6 py-4 bg-blue-500">
                    <a href="#" class="font-medium text-white hover:underline">Edit</a>
                </td>
            </tr>
            <tr class="bg-blue-600 border-b border-blue-400">
                <th class="px-2 py-2">
                    07.15 - 07.30
                </th>
                <td class="px-6 py-4">
                    Gray
                </td>
                <td class="px-6 py-4 bg-blue-500">
                    Phone
                </td>
                <td class="px-6 py-4">
                    $799
                </td>
                <td class="px-6 py-4 bg-blue-500">
                    <a href="#" class="font-medium text-white hover:underline">Edit</a>
                </td>
            </tr>
            <tr class="bg-blue-600 border-blue-40">
                  <th class="px-2 py-2">
                    07.15 - 07.30
                </th>
                <td class="px-6 py-4">
                    Red
                </td>
                <td class="px-6 py-4 bg-blue-500">
                    Wearables
                </td>
                <td class="px-6 py-4">
                    $999
                </td>
                <td class="px-6 py-4 bg-blue-500">
                    <a href="#" class="font-medium text-white hover:underline">Edit</a>
                </td>
            </tr>
        </tbody>
    </table>
</div>

    `
})
