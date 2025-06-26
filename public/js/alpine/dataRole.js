const dataRole =()=> {
    return {
        listRole: [],
        additionRole: {role_id: '', name: ''},
        currentView: 'table',
        errors: { role_id: '', name: ''},
        isLoading: false,
        isValid: true,
        fieldLabels: { name: 'Nama', role_id: 'Jabatan'},
        init() {
            this.getDataRole();
            this.$watch('additionRole.role_id', (value)=> { // melakukan watch reaktif
                if (value != '') { this.errors.role_id = '' }
            })
        },
        async getDataRole() {
            try {
                const result = await axios.get('/listRole');
                this.listRole = result.data.data
            } catch (error) {
                console.log(error)
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
                if (result) {
                    console.log(result);
                }
                successNotification(result.data.message)
                this.getDataRole()
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    let responseErrors = error.response.data.errors;
                    for (let key in responseErrors) {
                        this.errors[key] = responseErrors[key][0];
                    }
                    this.isLoading = false
                }
            }
        },

        async deleteConfirmation(additionId) {
            confirmDelete('Yakin dihapus?', async (result)=>{
                if(!result.isConfirmed) {
                    return
                }
            await swalLoading('Menghapus Addition Role..',async (result)=> {
                try {
                    let result = await axios.delete(`/addition-role/${additionId}`)
                    successNotification(result.data.message)
                    this.getDataRole()
                } catch (error) {
                    console.log(error)
                }
            });
            })
        },
    }
}
