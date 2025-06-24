const dataRole =()=> {
    return {
        listRole: [],
        additionRole: {roleId: '', name: ''},
        currentView: 'table',
        errors: { roleId: '', name: ''},
        isLoading: false,
        init() {
            this.getDataRole()
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
            this.currentView = 'table'
            this.resetField()
        },

        resetField() {
            Object.assign(this.additionRole, {
                name: '',
                roleId: '',
            });
        },

        async storeAdditionRole() {
            let sendDataRole = {
                role_id: this.additionRole.roleId,
                name: this.additionRole.name
            }
            try {
                let result = await axios.post('store-role', sendDataRole);
                this.closeCreateForm()
                this.resetField()
                successNotification(result.data.message)
                this.getDataRole()
            } catch (error) {

            }
        },

        async deleteConfirmation(additionId) {
            deleteConfirmation('Yakin dihapus?', async (result)=>{
                if(!result.isConfirmed) {
                    return
                }
            await swalLoading('Menghapus Data Role',async (result)=> {
                try {
                    await axios.delete(`/addition-role/${additionId}`)
                } catch (error) {
                    console.log(error)
                }
            });
            })
        },
    }
}
