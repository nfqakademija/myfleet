<template>
	<div class=" col-md-10 NotesTabs">
		<div class="col-md-12 NotesTabs-tabs">
			<div
				class="col-md-4 NotesTabs-tab"
				:class="{ 'NotesTabs-tab--active': currentTab === tab.id}"
				v-for="(tab, i) in tabs"
				:key="i"
				@click="switchTab(tab.id)"
			>
				<strong>{{ tab.name }}</strong>
			</div>
		</div>
		<div class="col-md-12 NotesTabs-content">
			<NotesTable
				:type="currentTabInfo.type"
				:notes="currentTabInfo.notes"
			/>
		</div>
	</div>
</template>

<script>
	import NotesTable from "./NotesTable";

    export default {
        components: {
            NotesTable,
        },
        name: 'NoteTabs',
	    data() {
            return {
                tabs: [
                    {
                        id: 1,
                        name: 'Užduotys',
                    },
                    {
                        id: 2,
                        name: 'Įvykiai',
                    },
                    {
                        id: 3,
                        name: 'Išlaidos',
                    },
                ],
                currentTab: 1,
                data: [
                    {
                        type: 1,
                        notes: [
                            {
                                id: 1,
                                name: 'Nuplauti automobilį',
                                created: '2019-12-12',
                                comment: 'Reikia nuplauti ir išsiurbliuoti',
                                status: 'Neatlikta',
                            },
                            {
                                id: 1,
                                name: 'Nuplauti automobilį',
                                created: '2019-12-12',
                                comment: 'Reikia nuplauti ir išsiurbliuoti',
                                status: 'Neatlikta',
                            },
                            {
                                id: 1,
                                name: 'Nuplauti automobilį',
                                created: '2019-12-12',
                                comment: 'Reikia nuplauti ir išsiurbliuoti',
                                status: 'Neatlikta',
                            },
                        ]
                    },
                    {
                        type: 2,
                        notes: [
                            {
                                id: 1,
                                name: 'Eismo taisyklių pažeidimas',
                                created: '2019-11-10',
                                comment: 'Viršytas greitis',
                            },
                            {
                                id: 1,
                                name: 'Eismo taisyklių pažeidimas',
                                created: '2019-11-10',
                                comment: 'Viršytas greitis',
                            },
                            {
                                id: 1,
                                name: 'Eismo taisyklių pažeidimas',
                                created: '2019-11-10',
                                comment: 'Viršytas greitis',
                            },
                        ],
                    },
                    {
                        type: 3,
                        notes: [
                            {
                                id: 1,
                                name: 'Tech. apžiūros mokestis',
                                created: '2019-10-10',
                                cost: 25,
                                comment: 'Atlikta Tech. apžiūra',
                            },
                            {
                                id: 1,
                                name: 'Tech. apžiūros mokestis',
                                created: '2019-10-10',
                                cost: 25,
                                comment: 'Atlikta Tech. apžiūra',
                            },
                            {
                                id: 1,
                                name: 'Tech. apžiūros mokestis',
                                created: '2019-10-10',
                                cost: 25,
                                comment: 'Atlikta Tech. apžiūra',
                            },
                        ],
                    }
                ]
            }
	    },
        methods: {
            switchTab(tab) {
                this.currentTab = tab;
            }
        },
        computed: {
            currentTabInfo() {
                return this.data.find(element => {
                    return element.type === this.currentTab;
                });
            },
        },
    }
</script>

<style scoped>
	@import '../../css/components/NotesTabs.scss';
</style>