
<template x-if="listMajor">
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
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(major, index) in listMajor" :key="index">
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-6 py-4" x-text="index + 1"></td>
                            <th x-text="major.name"
                                scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            </th>
                            <th x-text="major.slug"
                                scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            </th>
                             <th x-text="major.initial"
                                scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            </th>
                            <td class="px-2 py-4">
                                <template x-if="major.user && major.user.id">
                                    <span  x-text="major.user.name" class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-gray-700 dark:text-green-400 border border-green-400">
                                    </span>
                                </template>
                            </tid>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
</template>
