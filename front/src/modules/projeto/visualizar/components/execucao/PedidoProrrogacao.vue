<template>
    <div v-if="loading">
        <carregando :text="'Carregando Pedido de Prorrogação'"/>
    </div>
    <div v-else>
        <v-card>
            <v-card-title>
                <h6>Pedido de Porrogação</h6>
            </v-card-title>
            <v-data-table
                    :headers="headers"
                    :items="dados"
                    class="elevation-1 container-fluid mb-2"
                    rows-per-page-text="Items por Página"
                    :rows-per-page-items="[10, 25, 50, {'text': 'Todos', value: -1}]"
                    no-data-text="Nenhum dado encontrado"
            >
                <template slot="items" slot-scope="props">
                    <td class="text-xs-right">{{ props.item.DtPedido | formatarData }}</td>
                    <td class="text-xs-right">{{ props.item.DtInicio | formatarData }}</td>
                    <td class="text-xs-right">{{ props.item.DtFinal | formatarData }}</td>
                    <td class="text-xs-left" v-html="props.item.Observacao"></td>
                    <td class="text-xs-left" v-html="props.item.Estado"></td>
                    <td class="text-xs-left">{{ props.item.Usuario }}</td>
                </template>
                <template slot="pageText" slot-scope="props">
                    Items {{ props.pageStart }} - {{ props.pageStop }} de {{ props.itemsLength }}
                </template>
            </v-data-table>
        </v-card>
    </div>
</template>
<script>
    import { mapGetters, mapActions } from 'vuex';
    import moment from 'moment';
    import Carregando from '@/components/CarregandoVuetify';

    export default {
        name: 'PedidoProrrogacao',
        props: ['idPronac'],
        components: {
            Carregando,
        },
        data() {
            return {
                loading: true,
                search: '',
                pagination: {
                    rowsPerPage: 10,
                    sortBy: 'fat',
                },
                selected: [],
                headers: [
                    {
                        align: 'center',
                        text: 'Dt.Pedido',
                        sortable: false,
                        value: 'DtPedido',
                    },
                    {
                        align: 'center',
                        text: 'Dt.Início',
                        value: 'DtInicio',
                    },
                    {
                        align: 'center',
                        text: 'Dt.Final',
                        value: 'DtFinal',
                    },
                    {
                        align: 'left',
                        text: 'Observações',
                        value: 'Observacao',
                    },
                    {
                        text: 'Estado',
                        align: 'left',
                        value: 'Estado',
                    },
                    {
                        text: 'Analista',
                        align: 'left',
                        value: 'Usuario',
                    },
                ],
            };
        },
        filters: {
            formatarData(date) {
                if (date.length === 0) {
                    return '-';
                }
                return moment(date).format('DD/MM/YYYY');
            },
        },
        mounted() {
            if (typeof this.dadosProjeto.idPronac !== 'undefined') {
                this.buscarPedidoProrrogacao(this.dadosProjeto.idPronac);
            }
        },
        computed: {
            ...mapGetters({
                dadosProjeto: 'projeto/projeto',
                dados: 'projeto/pedidoProrrogacao',
            }),
        },
        methods: {
            ...mapActions({
                buscarPedidoProrrogacao: 'projeto/buscarPedidoProrrogacao',
            }),
        },
        watch: {
            dados() {
                this.loading = false;
            },
        },
    };
</script>
