<template>
    <div v-if="Object.keys(dadosReadequacao.dadosReadequacoesDevolvidas).length > 0">
        <v-container fluid>
            <v-card>
                <v-card-title>
                    <h6>Readequações Devolvidas</h6>
                </v-card-title>
                <v-data-table
                        :headers="headers"
                        :items="dadosReadequacao.dadosReadequacoesDevolvidas"
                        class="elevation-1 container-fluid mb-2"
                        rows-per-page-text="Items por Página"
                        no-data-text="Nenhum dado encontrado"
                        :rows-per-page-items="[10, 25, 50, {'text': 'Todos', value: -1}]"
                >
                    <template slot="items" slot-scope="props">
                        <td class="text-xs-left">{{ props.item.dsReadequacao }}</td>
                        <td class="text-xs-right">{{ props.item.dtAvaliador | formatarData }}</td>
                        <td class="text-xs-left" v-html="props.item.dsAvaliacao"></td>
                    </template>
                    <template slot="pageText" slot-scope="props">
                        Items {{ props.pageStart }} - {{ props.pageStop }} de {{ props.itemsLength }}
                    </template>
                </v-data-table>
            </v-card>
        </v-container>
    </div>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex';
    import moment from 'moment';

    export default {
        name: 'ReadequacoesDevolvidas',
        props: ['idPronac'],
        data() {
            return {
                loading: true,
                search: '',
                pagination: {
                    sortBy: 'fat',
                },
                selected: [],
                headers: [
                    {
                        text: 'TIPO DE READEQUAÇÃO',
                        align: 'left',
                        value: 'dsReadequacao',
                    },
                    {
                        text: 'DATA',
                        align: 'center',
                        value: 'dtAvaliador',
                    },
                    {
                        text: 'AVALIAÇÃO',
                        align: 'left',
                        value: 'dsAvaliacao',
                    },
                ],
            };
        },
        mounted() {
            if (typeof this.dadosProjeto.idPronac !== 'undefined') {
                this.buscarDadosReadequacoes(this.dadosProjeto.idPronac);
            }
        },
        filters: {
            formatarData(date) {
                if (date.leggth === 0) {
                    return '-';
                }
                return moment(date).format('DD/MM/YYYY');
            },
        },
        computed: {
            ...mapGetters({
                dadosProjeto: 'projeto/projeto',
                dadosReadequacao: 'projeto/dadosReadequacoes',
            }),
        },
        watch: {
            dados() {
                this.loading = false;
            },
        },
        methods: {
            ...mapActions({
                buscarDadosReadequacoes: 'projeto/buscarDadosReadequacoes',
            }),
        },
    };
</script>

