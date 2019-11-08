<template>
	<div class=" col-md-10 NotesTabs">
		<div class="col-md-12 NotesTabs-tabs">
			<div
				class="col-md-3 NotesTabs-tab"
				:class="{ 'NotesTabs-tab--active': currentTab === tab.id}"
				v-for="(tab, i) in tabs"
				:key="i"
				@click="switchTab(tab.id)"
			>
				<strong>{{ tab.name }}</strong>
			</div>
		</div>
		<div class="col-md-12 NotesTabs-content">
			<Properties
			    v-if="currentTab === 1"
				:properties="properties"
			/>
			<NotesCard
			    v-if="currentTab !== 1"
				v-for="(card, i) in currentTabInfo.cards"
				:key="i"
				:card="card"
				:cardType="currentTabInfo.type"
			/>
		</div>
	</div>
</template>

<script>
	import NotesTable from "./NotesTable";
	import NotesCard from "./NotesCard";
	import Properties from "./Properties";

    export default {
        components: {
            NotesTable,
	        NotesCard,
	        Properties,
        },
        name: 'NoteTabs',
	    data() {
            return {
                tabs: [
	                {
	                    id: 1,
	                    name: 'Informacija',
	                },
                    {
                        id: 2,
                        name: 'Užduotys',
                    },
                    {
                        id: 3,
                        name: 'Įvykiai',
                    },
                    {
                        id: 4,
                        name: 'Išlaidos',
                    },
                ],
                currentTab: 1,
                properties: {
                    name: 'BMW',
                    model: 'M5',
                    bodyType: 'Sedanas',
                    year: '2009-10',
                    countryNumber: 'HHH555',
                    vinCode: '1ZVBP8AM5D5230076',
                    comment: 'Graži mašina',
                },
                data: [
                    {
                        type: 2,
                        cards: [
                            {
                                id: 1,
                                name: 'Nuplauti automobilį',
                                createdAt: '2019-12-12',
                                comment: 'Reikia nuplauti ir išsiurbliuoti',
                                status: 'Neatlikta',
                            },
                            {
                                id: 1,
                                name: 'Nuplauti automobilį',
                                createdAt: '2019-12-12',
                                comment: 'Reikia nuplauti ir išsiurbliuoti',
                                status: 'Neatlikta',
                            },
                            {
                                id: 1,
                                name: 'Nuplauti automobilį',
                                createdAt: '2019-12-12',
                                comment: 'Reikia nuplauti ir išsiurbliuoti',
                                status: 'Neatlikta',
                            },
                        ]
                    },
                    {
                        type: 3,
                        cards: [
                            {
                                id: 1,
                                name: 'Eismo taisyklių pažeidimas',
                                createdAt: '2019-11-10',
                                comment: 'Viršytas greitis',
                            },
                            {
                                id: 1,
                                name: 'Eismo taisyklių pažeidimas',
                                createdAt: '2019-11-10',
                                comment: 'Viršytas greitis',
                            },
                            {
                                id: 1,
                                name: 'Eismo taisyklių pažeidimas',
                                createdAt: '2019-11-10',
                                comment: 'Viršytas greitis',
                            },
                        ],
                    },
                    {
                        type: 4,
                        cards: [
                            {
                                id: 1,
                                name: 'Tech. apžiūros mokestis',
                                createdAt: '2019-10-10',
                                cost: 25,
                                comment: 'Atlikta Tech. apžiūra',
                            },
                            {
                                id: 1,
                                name: 'Tech. apžiūros mokestis',
                                createdAt: '2019-10-10',
                                cost: 25,
                                comment: 'Atlikta Tech. apžiūra',
                            },
                            {
                                id: 1,
                                name: 'Tech. apžiūros mokestis',
                                createdAt: '2019-10-10',
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
                if (this.currentTab !== 1) {
                    return this.data.find(element => {
                        return element.type === this.currentTab;
                    });
                } else {
                    return []
                }
            },
        },
    }
</script>

<style scoped>
	@import '../../../../css/components/NotesTabs.scss';
</style>