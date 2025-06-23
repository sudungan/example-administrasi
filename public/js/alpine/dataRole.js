// import axios from 'https://cdn.jsdelivr.net/npm/axios@1.6.2/dist/esm/axios.min.js';
const dataRole =()=> {
    return {
        listRole: [],
        currentView: 'table',
        init() {
            this.getDataRole()
        },
        async getDataRole() {
            try {
                const result = await axios.get('/getListUser');
                this.listRole = result.data.data
            } catch (error) {
                console.log(error)
            }
        },
        showFormCreate() {
            this.currentView = 'create'
            console.log('membuka form')
        }
    }
}
