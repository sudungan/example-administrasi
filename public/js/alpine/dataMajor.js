const dataMajor =()=> {
    return {
        listMajor: [],
        currentView: 'table',
        isLoading: false,
        major: {id: '', name: '', user_id: ''},
        currentDataMajor: {id: '', name: '', user_id: ''},
        changedFields: {},
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
                // console.log(this.listMajor)
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
            let isAnyFilled = Object.values(this.major).some( value => value !== '')
            console.log('ada data ?',isAnyFilled)
            if (isAnyFilled) {
                // menggunakan cancelConfirmation dari public js/helper
                cancelConfirmation('Yakin membatalkan?', (result)=> {
                    if (result.isConfirmed) {
                        this.currentView = 'table';
                        this.resetField();
                        this.resetErrors()
                    }
                });
            }else {
                this.currentView = 'table'
                this.resetErrors()
            }
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
        },
        async editMajor(majorId) {
            try {
                const result = await axios.get(`/edit-major/${majorId}`);
                this.currentView = 'edit';
                this.major = result.data.data;
                this.currentDataMajor = { ...this.major}; // spread mengisi data objek
                console.log('ini dari edit', this.currentDataMajor)
            } catch (error) {
                console.log(error)
            }
        },
        async updateMajor() {
             try {
                let majorUpdate = {
                    id: this.major.id,
                    user_id: this.changedFields.user_id || this.major.user_id,
                    name: this.changedFields.name || this.major.name
                }

                // this.isLoading = true;
                const result = await axios.put(`/update-major/${majorUpdate.id}`, majorUpdate);
                  console.log(this.majorUpdate.id)
                this.isLoading = false;
                this.resetErrors()
                this.currentView = 'table';
                successNotification(result.data.message)
                await this.getDataRole()

            } catch (error) {
                if (error.response && error.response.status == 409) {
                        this.isLoading = false
                        this.resetErrors();
                        this.currentView = 'table'
                        swalNotificationConflict(error.response.data.message)
                }

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
        },
        closeEditForm() {
            const isChanged =
                this.major.user_id !== this.currentDataMajor.user_id ||
                this.major.name !== this.currentDataMajor.name;

                isChanged
                ? cancelConfirmation('Yakin membatalkan?', result =>  result.isConfirmed && (this.currentView = 'table'))
                : this.currentView = 'table';
        },
        deleteConfirmation(majorId) {
            confirmDelete('Yakin dihapus?', async (result)=>{
                if(!result.isConfirmed) {
                    return
                }
                await swalLoading('Menghapus Data Jurusan..',async (result)=> {
                    try {
                        let result = await axios.delete(`/delete-major/${majorId}`)
                        successNotification(result.data.message)
                        this.getDataMajor()
                    } catch (error) {
                        if (error.response && error.response.status == 409) {
                                swalNotificationConflict(error.response.data.message)
                        }else {
                            swalInternalServerError(error.response.data.message) // http code 500
                        }
                    }
                });
            })
        },
        changeDataMajor(event) {
            const field = event.target.id;
            const value = event.target.value;
            const originalValue = this.currentDataMajor[field];
            console.log(originalValue)
            value !== originalValue ?  this.changedFields[field] = value :  delete this.changedFields[field];
        }
    }
}

// export default dataMajor;
