<template>
    <div>
        <v-card-title>
            <v-spacer></v-spacer>
            <v-text-field
                    v-model="search"
                    append-icon="search"
                    label="Pesquisar"
                    single-line
                    hide-details
                    height="35px"
            ></v-text-field>
        </v-card-title>
        <v-data-table
                :headers="cabecalho"
                :items="dados.items"
                :pagination.sync="pagination"
                hide-actions
                :search="search"
        >
            <template slot="items" slot-scope="props">
                <td class="text-xs-center">{{ props.index+1 }}</td>
                <td class="text-xs-center">
                    <v-flex>
                        <div>
                            <v-btn :href="'/projeto/#/'+ props.item.idPronac">{{ props.item.PRONAC }}</v-btn>
                        </div>
                    </v-flex>
                </td>
                <td class="text-xs-center">{{ props.item.NomeProjeto }}</td>
                <td class="text-xs-center">
                    <VisualizarParecer
                        :obj="props.item"
                        :id-pronac="props.item.idPronac"
                    >

                    </VisualizarParecer>
                </td>
                <td class="text-xs-center">
                    <Devolver
                        v-if="usuario"
                        :idPronac="String(props.item.IdPronac)"
                        :atual="estado"
                        :proximo="proximoEstado()"
                        :nomeProjeto="props.item.NomeProjeto"
                        :pronac="props.item.PRONAC"
                        :idTipoDoAtoAdministrativo="atoAdministrativo"
                        :usuario="getUsuario"
                        :tecnico="devolucaoLaudo"
                    >
                    </Devolver>
                </td>
                <td v-if="estado == Const.ESTADO_ANALISE_LAUDO" class="text-xs-center">
                    <v-btn flat icon color="teal darken-1"
                           id="emitirLaudo"
                           @click.native="sincState(props.item.IdPronac)"
                           :to="{ name: 'EmitirLaudoFinal', params:{ id:props.item.IdPronac }}">
                        <v-tooltip bottom>
                            <v-icon slot="activator" class="material-icons">gavel</v-icon>
                            <span>Emitir Laudo</span>
                        </v-tooltip>
                    </v-btn>
                </td>
                <td v-if="estado == Const.ESTADO_LAUDO_FINALIZADO" class="text-xs-center">
                    <v-btn flat icon color="teal darken-1"
                           id="assinarLaudo"
                           :href="'/assinatura/index/assinar-projeto?IdPRONAC='+props.item.IdPronac+'&idTipoDoAtoAdministrativo=623'">
                        <v-tooltip bottom>
                            <v-icon slot="activator" class="material-icons">edit</v-icon>
                            <span>Assinar Laudo</span>
                        </v-tooltip>
                    </v-btn>
                </td>
                <td v-if="estado == Const.ESTADO_AGUARDANDO_ASSINATURA_LAUDO ||
                          estado == Const.ESTADO_AVALIACAO_RESULTADOS_FINALIZADA"
                    class="text-xs-center"
                >
                    <v-btn flat icon color="teal darken-1"
                           id="visualizarLaudo"
                           @click.native="sincState(props.item.IdPronac)"
                           :to="{ name: 'VisualizarLaudo', params:{ id:props.item.IdPronac }}">
                        <v-tooltip bottom>
                            <v-icon slot="activator" class="material-icons">visibility</v-icon>
                            <span>Visualizar Laudo</span>
                        </v-tooltip>
                    </v-btn>
                </td>
            </template>
            <template slot="no-data">
                <v-alert :value="true" color="error" icon="warning">
                    Nenhum dado encontrado ¯\_(ツ)_/¯

                </v-alert>
            </template>
            <v-alert slot="no-results" :value="true" color="error" icon="warning">
                Não foi possível encontrar um projeto com a palavra chave '{{search}}'.
            </v-alert>
        </v-data-table>
        <div class="text-xs-center pt-2">
            <div v-if="pagination.totalItems" class="text-xs-center">
                <v-pagination
                        v-model="pagination.page"
                        :length="pages"
                        :total-visible="3"
                        color="primary"
                ></v-pagination>
            </div>
        </div>
    </div>
</template>

<script>
import ModalTemplate from '@/components/modal';
import { mapGetters, mapActions } from 'vuex';
import Const from '../../const';
import Devolver from '../components/Devolver';
import VisualizarParecer from '../components/VisualizarParecer';

export default {
    name: 'Painel',
    props: ['dados', 'estado'],
    data() {
        return {
            devolucaoLaudo: {
                idAgente: 0,
                nome: 'sysLaudo',
            },
            pagination: {
                rowsPerPage: 10,
            },
            searchLength: 0,
            search: '',
            dialog: false,
            cabecalho: [
                {
                    align: 'center',
                    text: '#',
                    sortable: false,
                },
                {
                    align: 'center',
                    text: 'PRONAC',
                    value: 'PRONAC',
                },
                {
                    align: 'center',
                    text: 'Nome Do Projeto',
                    value: 'NomeProjeto',
                },
                {
                    align: 'center',
                    text: 'Manifestação',
                    value: 'dsResutaldoAvaliacaoObjeto',
                },
                {
                    align: 'center',
                    text: 'Devolver',
                    sortable: false,

                },
                {
                    align: 'center',
                    text: 'Ação',
                    sortable: false,
                },
            ],
            Const,
        };
    },
    components: {
        ModalTemplate,
        Devolver,
        VisualizarParecer,
    },
    methods: {
        ...mapActions({
            requestEmissaoParecer: 'avaliacaoResultados/getDadosEmissaoParecer',
            getLaudoFinal: 'avaliacaoResultados/getLaudoFinal',
        }),
        sincState(id) {
            this.requestEmissaoParecer(id);
            this.getLaudoFinal(id);
        },
        proximoEstado() {
            let proximo = '';

            switch (this.estado) {
            case Const.ESTADO_ANALISE_LAUDO:
                proximo = Const.ESTADO_ANALISE_PARECER;
                break;
            case Const.ESTADO_LAUDO_FINALIZADO:
                proximo = Const.ESTADO_ANALISE_LAUDO;
                break;
            case Const.ESTADO_AGUARDANDO_ASSINATURA_LAUDO:
                proximo = Const.ESTADO_ANALISE_LAUDO;
                break;
            case Const.ESTADO_AVALIACAO_RESULTADOS_FINALIZADA:
                proximo = Const.ESTADO_ANALISE_LAUDO;
                break;
            default:
                proximo = '';
            }
            return proximo;
        },
    },
    computed: {
        ...mapGetters({
            getUsuario: 'autenticacao/getUsuario',
        }),
        pages() {
            if (this.pagination.rowsPerPage == null ||
                    this.pagination.totalItems == null
            ) return 0;
            return Math.ceil(this.pagination.totalItems / this.pagination.rowsPerPage);
        },
        atoAdministrativo() {
            let ato = Const.ATO_ADMINISTRATIVO_PARECER_TECNICO;

            if (
                this.usuario &&
                    (
                        Const.PERFIL_DIRETOR === this.getUsuario.grupo_ativo
                        || Const.PERFIL_SECRETARIO === this.getUsuario.grupo_ativo
                    )
            ) {
                ato = Const.ATO_ADMINISTRATIVO_LAUDO_FINAL;
            }

            return ato;
        },
        usuario() {
            return (this.getUsuario !== undefined && Object.keys(this.getUsuario).length > 0);
        },
    },
    watch: {
        dados() {
            if (this.dados.items !== undefined) {
                this.pagination.totalItems = this.dados.items.length;
            }
        },
    },
};
</script>
