const dataMajor =()=> {
    return {
        listMajor: [],
        currentView: 'table',
        major: {},
        init() {
            this.tester()
        },
        async getDataMajor() {
            const result = await axios.get('');
        },
        showFormCreate() {
            this.currentView = 'create';
        },
        tester() {
            console.log('sedang berjalan');
        }
    }
}
