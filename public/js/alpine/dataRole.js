const dataRole =()=> {
    return {
        listRole: [], // state tempat menampung seluruh data list role
        additionRole: {role_id: '', name: ''}, // state tempat menampung data object baru
        originalAdditionRole: {id: '',role_id: '', name: ''}, // state tempat orignal atau clone data objek
        changedFields: {}, // state tempat menampung data dari field yang nilainya berubah
        errors: { role_id: '', name: ''},
        currentView: 'table',
        isLoading: false,
        isValid: true,
        fieldLabels: { name: 'Nama', role_id: 'Jabatan'},
        init() {
            this.getDataRole();
        },
        async getDataRole() {
            try {
                const result = await axios.get('/list-role');
                this.listRole = result.data.data
            } catch (error) {
            }
        },
        showFormCreate() {
            this.currentView = 'create'
        },
        closeCreateForm() {
            let isAnyFilled = Object.values(this.additionRole).some( value => value !== '')
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
            Object.assign(this.additionRole, {
                name: '',
                role_id: '',
            });
        },

        resetErrors(){
            Object.assign(this.errors, {
                name: '',
                role_id: ''
            })
        },

        async storeAdditionRole() {
            try {
                // melakukan validasi inputan melalui object yang dibongkar / looping
                this.isValid = true;
                for (let key in this.additionRole) {
                    if (!this.additionRole[key].toString().trim()) {
                        let label  = this.fieldLabels[key] || key;
                            this.errors[key] = `${label} tidak boleh kosong`;
                            this.isValid = false;
                    }else {
                        this.errors[key] = '';
                    }
                }

                if (!this.isValid) return

                let sendDataRole = {
                    role_id: this.additionRole.role_id,
                    name: this.additionRole.name
                }
                this.isLoading = true;
                let result = await axios.post('store-role', sendDataRole);
                this.isLoading = false
                this.resetField()
                this.closeCreateForm()
                this.currentView = 'table'
                successNotification(result.data.message)
                this.getDataRole()
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

        async deleteConfirmation(additionRoleId) {
            confirmDelete('Yakin dihapus?', async (result)=>{
                if(!result.isConfirmed) {
                    return
                }
                await swalLoading('Menghapus Addition Role..',async (result)=> {
                    try {
                        let result = await axios.delete(`/delete-addition-role/${additionRoleId}`)
                        successNotification(result.data.message)
                        this.getDataRole()
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

        async editAdditionRole(additionRoleId) {
            try {
                const result = await axios.get(`edit-addition-role/${additionRoleId}`);
                this.currentView = 'edit'
                this.additionRole = result.data.data
                this.originalAdditionRole = { ...this.additionRole}; // spread mengisi data objek
                console.log('ini dari edit', this.originalAdditionRole)
            } catch (error) {
               swalInternalServerError(error.response.data.message) // http code 500
            }
        },
        closeEditFormAdditionRole() {
            const isChanged =
                this.additionRole.role_id !== this.originalAdditionRole.role_id ||
                this.additionRole.name !== this.originalAdditionRole.name;

                isChanged
                ? cancelConfirmation('Yakin membatalkan?', result =>  result.isConfirmed && (this.currentView = 'table'))
                : this.currentView = 'table';
        },
        changeDataRole(event) {
            const field = event.target.id;
            const value = event.target.value;
            const originalValue = this.originalAdditionRole[field];
            value !== originalValue ?  this.changedFields[field] = value :  delete this.changedFields[field];
        },

        async updateAdditionRole() {
            try {
                let additionRoleUpdate = {
                    id: this.additionRole.id,
                    role_id: this.changedFields.role_id || this.additionRole.role_id,
                    name: this.changedFields.name || this.additionRole.name
                }
                this.isLoading = true;
                const result = await axios.put(`/update-addition-role/${additionRoleUpdate.id}`, additionRoleUpdate);
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
    }
}

// export default dataRole;
