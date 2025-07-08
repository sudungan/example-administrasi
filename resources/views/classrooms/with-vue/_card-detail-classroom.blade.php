<nav class="flex mb-3" aria-label="Breadcrumb">
  <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
    <li class="inline-flex items-center">
      <a href=" {{ route('dashboard') }}" wire:navigate class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
        Dashboard
      </a>
    </li>
    <li>
      <div class="flex items-center">
        <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
        </svg>
        <a href="{{ route('classrooms.index') }}" wire:navigate class="ms-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ms-2 dark:text-gray-400 dark:hover:text-white">Kelas</a>
      </div>
    </li>
    <li aria-current="page">
      <div class="flex items-center">
        <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
        </svg>
        <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">Detail</span>
      </div>
    </li>
  </ol>
</nav>

<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <caption class="p-5 text-lg font-semibold text-left rtl:text-right text-gray-900 bg-white dark:text-white dark:bg-gray-800">
            @{{ detailClassroom.name }} - @{{ detailClassroom.major?.initial }}
        </caption>
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    Wali Kelas
                </th>
                <th scope="col" class="px-6 py-3">
                    Siswa
                </th>
            </tr>
        </thead>
        <tbody>
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                   @{{ detailClassroom.teacher?.name }}
                </th>

                <td v-if="detailClassroom.students.length > 0" class="px-6 py-4">
                    <div class="inline-flex gap-2" v-for="(student, index) in detailClassroom.students" :key="index">
                        <span
                         :class="['text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm border',
                            detailClassroom.major?.initial?.toLowerCase() != 'ak'
                                ? 'bg-green-100 text-green-800 border-green-400 dark:bg-gray-700 dark:text-green-400'
                                : 'bg-pink-100 text-pink-800 border-pink-400 border-pink-400 dark:bg-gray-700 dark:text-pink-400'
                        ]">
                         @{{student.name}}
                         </span>
                    </div>
                </td>
                <td v-else class="px-6 py-4">
                    <span  class="bg-yellow-100 text-yellow-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-gray-700 dark:text-yellow-300 border border-yellow-300">
                        Belum ada data siswa..
                    </span>
                </td>
            </tr>
        </tbody>
    </table>
</div>
