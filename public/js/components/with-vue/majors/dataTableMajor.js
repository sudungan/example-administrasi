const { defineComponent, ref, watch, computed } = Vue

export default defineComponent({
    name: 'dataTableMajor',
    props: {},
    emits: [''],
    setup(props, {emit}) {
        const editMajor = (majorId)=> {

        }
        return {
            editMajor,
        }
    },
    template: `
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            #
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Nama Jurusan
                        </th>
                            <th scope="col" class="px-6 py-3">
                            Slug Jurusan
                        </th>
                            <th scope="col" class="px-6 py-3">
                            Inisial Jurusan
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Kepala Jurusan
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    `
});
