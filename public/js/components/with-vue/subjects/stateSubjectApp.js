    import dataTableSubject from "./dataTableSubject.js"
    import formCreateSubject from "./formCreateSubject.js"
    import loadingTableSubject from "./loadingTableSubject.js"
    import createTeacherColour from "./createTeacherColour.js"
    import cardShowListSubject from "./cardShowListSubject.js"
    import cardAddSubjectToClassroom from "./cardAddSubjectToClassroom.js"
    const { createApp, ref, reactive, onMounted, nextTick  } = Vue
export default function stateSubjectApp () {

    createApp({
        components: {
            dataTableSubject, formCreateSubject, loadingTableSubject, createTeacherColour, cardShowListSubject,
            cardAddSubjectToClassroom
        },
        setup() {
            const currentView = ref("loading-table")
            const message = ref('Hello Vue!')
            const listTeacherSubject = ref([])
            const hasTeacherBaseColour = ref(null)
            const dataTeacherBy = ref({ id: '', name: '' })
            const showListSubjectsTeacher = ref([])
            const dataTeacher = ref({})
            const totalJpTeacher = ref([])
            const dataAddSubjectTo = ref({})
            const listClassroom = ref([])
            const showFormCreate =()=> currentView.value = 'create-teacher-colour'
            const baseColour = ref([
                { id:'1', item:'blue'}, { id:'2', item:'gray'}, { id:'3', item:'red'}, { id:'4', item:'green'}, { id:'5', item:'yellow'},
                { id:'6', item:'indigo'}, { id:'7', item:'purple'}, { id:'8', item:'pink'}, { id:'9', item:'lime'}, { id:'10', item:'indigo'},
                { id:'11', item:'rose'}, { id:'12', item:'cyan'}, { id:'13', item:'emerald'}, { id:'14', item:'violet'}, { id:'15', item:'sky'},
            ])

            const baseCssColour = {
                blue: '#3b82f6',
                gray: '#6b7280',
                red: '#ef4444',
                green: '#10b981',
                yellow: '#facc15',
                indigo: '#6366f1',
                purple: '#8b5cf6',
                pink: '#ec4899',
                lime: '#84cc16',
                rose: '#f43f5e',
                cyan: '#06b6d4',
                emerald: '#10b981',
                violet: '#8b5cf6',
                sky: '#0ea5e9'
            }

            onMounted(async ()=> {
                await getListTeacherSubject()
                await getTotalJpTeacher()
                await getListClassroom()
                await nextTick()
            });

            async function getListTeacherSubject() {
                try {
                    let result = await axios.get('list-teacher-subject'); // mengambil data guru dengan subjectnya
                    listTeacherSubject.value = result.data.data
                    currentView.value = 'table'

                } catch (error) {
                    console.log('error:', error)
                }
            }

            // fungsi mengambil semua totalJP
            async function getTotalJpTeacher() {
                try {
                    let result = await axios.get(`total-jp-teachers`)
                    totalJpTeacher.value = result.data.data
                } catch (error) {
                    console.log('error: ', error)
                }
            }

            async function refreshListSubject() {
                await getListTeacherSubject(),
                await getTotalJpTeacher()
            }

            // fungsi untuk menghandle passing data dari BE ke component lain
            function handlePassingData(data) {
                dataTeacher.value = data
            }

            function handleAddSubjectTo(data) {
                currentView.value = data.component // nama component: add-subject-to
                dataAddSubjectTo.value = data
                console.log('data', data)
            }

            async function handleSelectTeacher(data) {
                try {
                    dataTeacherBy.value = await getTeacherBy(data.teacherId)
                    hasTeacherBaseColour.value =  await checkBaseColour(data.teacherId)
                    if (hasTeacherBaseColour.value) {
                        currentView.value = data.component   // 'create-subject'
                    }else {
                        currentView.value = 'create-teacher-colour'
                    }
                } catch (error) {
                    console.log('error', error)
                }
            }

            // melakukan check base teacher colour pada guru yang tersedia
            async function checkBaseColour(teacherId) {
                try {
                    let result = await axios.get(`/check-base-colour-by/${teacherId}`)
                    return result.data.data
                } catch (error) {
                    console.log('error:', error)
                }
            }

            // mengambil data guru berdasarkan id
            async function getTeacherBy(teacherId) {
                try {
                    let result = await axios.get(`/teacher-by/${teacherId}`)
                   return result.data.data
                } catch (error) {
                    console.log('error', error)
                }
            }


            // mengambil data seluruh list kelas yang tersedia
            async function getListClassroom() {
                try {
                    let result = await axios.get('list-classroom');
                    listClassroom.value = result.data.data
                } catch (error) {
                    console.log('error:', error)
                }
            }

            return {
                message, currentView, showFormCreate, listClassroom,
                getListClassroom, baseColour, refreshListSubject, baseCssColour,
                dataTeacher, handlePassingData, listTeacherSubject, handleSelectTeacher,showListSubjectsTeacher,
                hasTeacherBaseColour, checkBaseColour, dataTeacherBy, getTeacherBy, handleAddSubjectTo, dataAddSubjectTo,
                getTotalJpTeacher, totalJpTeacher,
             }
        },
        template: `
            <!-- komponent untuk loading-data-table-subject -->
             <div v-show="currentView === 'loading-table'" class="relative shadow-md sm:rounded-lg">
                <loading-table-subject :visable-card="currentView" />
            </div>

            <!-- komponent untuk data-table-subject -->
             <div v-cloak v-show="currentView === 'table'" class="relative shadow-md sm:rounded-lg">
               <data-table-subject
                :visable-card="currentView"
                :data="listTeacherSubject"
                :data-provide-by="totalJpTeacher"
                @add="handleSelectTeacher"
                @add-subject-to="handleAddSubjectTo"
                @reload="refreshListSubject"
                />
            </div>

            <!-- komponent untuk show-list-subject-by-teacherId -->
             <div
                v-cloak
                v-show="currentView === 'show-list-subject-by'" class="relative sm:rounded-lg">

            </div>

            <!-- komponent untuk form-create-subject -->
             <div
                v-cloak
                v-show="currentView === 'create-subject'" class="relative sm:rounded-lg">
                <form-create-subject
                    :visable-card="currentView"
                    :classrooms="listClassroom"
                    @reload="refreshListSubject"
                    :dataPassingTeacher="hasTeacherBaseColour"
                    @back-to="currentView = $event"
                />
            </div>

             <!-- komponent untuk form-add-create-subject -->
             <div
                v-cloak
                v-show="currentView === dataAddSubjectTo.component" class="relative sm:rounded-lg">
                <card-add-subject-to-classroom
                    :visable-card="currentView"
                    :classrooms="listClassroom"
                    @reload="refreshListSubject"
                    :dataPassingTeacher="hasTeacherBaseColour"
                    @back-to="currentView = $event"
                />
            </div>

            <!-- komponent untuk form-create-teacher-colour -->
             <div
                v-cloak
                v-show="currentView === 'create-teacher-colour'"  class="relative sm:rounded-lg">
                <create-teacher-colour
                    :visable-card="currentView"
                    :provide-colour="baseColour"
                    :dataPassingTeacher="dataTeacherBy"
                    @change-to="currentView = $event"
                    @back-to="currentView = $event"
                    @send-back-data="handlePassingData"
                />
            </div>

        `
    }).mount('#app')
}
