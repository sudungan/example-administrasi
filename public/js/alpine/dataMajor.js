const dataMajor =()=> {
    return {
        listMajor: [],
        currentView: 'table',
        isLoading: false,
        major: {name: '', user_id: ''},
        errors: {name: '', user_id: ''},
        fieldLabels: { name: 'Nama Jurusan', user_id: 'Kanidat kepala Jurusan'},
        listTeacher: [],
        isValid: false,
        init() {
            this.getListTeacher()
            this.getDataMajor()
        },
        async getDataMajor() {
            try {
                const result = await axios.get('/list-major');
                this.listMajor = result.data.data;
                console.log(this.listMajor)
            } catch (error) {
                console.log(error)
            }
        },
        showFormCreate() {
            this.currentView = 'create';
        },
        async getListTeacher() {
            const result = await axios.get('/list-get-teacher');
            this.listTeacher = result.data.data;
        },
        closeCreateForm() {
            this.resetErrors()
            this.resetField()
            this.currentView = 'table'
        }, 
          resetField() {
            Object.assign(this.major, {
                name: '',
                user_id: '',
            });
        },

        resetErrors(){
            Object.assign(this.errors, {
                name: '',
                user_id: ''
            })
        },
        async storeMajor() {
            try {
                 this.isValid = true;
                for (let key in this.major) {
                    if (!this.major[key].toString().trim()) {
                        let label  = this.fieldLabels[key] || key;
                            this.errors[key] = `${label} tidak boleh kosong`;
                            this.isValid = false;
                    }else {
                        this.errors[key] = '';
                    }
                }

                if (!this.isValid) return

                let sendMajor = {
                    name: this.major.name,
                    user_id: this.major.user_id,
                }
                this.isLoading = true;
                const result = await axios.post('/store-major', sendMajor)
                this.isLoading = false
                this.resetField()
                this.closeCreateForm()
                this.currentView = 'table'
                successNotification(result.data.message)
                this.getDataMajor()
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    let responseErrors = error.response.data.errors;
                    for (let key in responseErrors) {
                        this.errors[key] = responseErrors[key][0];
                    }
                    this.isLoading = false
                }else {
                    swalInternalServerError(error.response.data.message) // http code 500
                }
            }
        }
    }
}

export default dataMajor;
