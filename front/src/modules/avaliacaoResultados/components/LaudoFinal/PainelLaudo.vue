<template>
    <v-container fluid>
        <v-subheader>
            <h2>{{route.meta.title}}</h2>
        </v-subheader>
        <v-card>
            <v-tabs
                centered
                color="primary"
                dark
                icons-and-text
            >
                <v-tabs-slider color="deep-orange accent-3"></v-tabs-slider>
                <v-tab href="#tab-0"
                       id="emAnalise"
                       v-if="getUsuario.grupo_ativo == Const.PERFIL_COORDENADOR_GERAL"
                >
                    <template v-if="Object.keys(getProjetosLaudoFinal).length == 0">
                        <v-progress-circular
                            indeterminate
                            color="primary"
                            dark
                        ></v-progress-circular>
                    </template>
                    <template v-else>
                        Em Analise
                        <v-icon>gavel</v-icon>
                    </template>
                </v-tab>
                <v-tab href="#tab-1"
                       id="assinar"
                       v-if="getUsuario.grupo_ativo == Const.PERFIL_COORDENADOR_GERAL"
                >
                     Assinar
                    <v-icon>done</v-icon>
                </v-tab>
                <v-tab href="#tab-2"
                       id="emAssinatura">
                     Em assinatura
                    <v-icon>done_all</v-icon>
                </v-tab>
                <v-tab href="#tab-3"
                       id="finalizados"
                       v-if="getUsuario.grupo_ativo == Const.PERFIL_COORDENADOR_GERAL"
                >
                     Finalizados
                    <v-icon>collections_bookmark</v-icon>
                </v-tab>

                <v-tab-item
                    :value="'tab-0'"
                    :key="0"
                    v-if="getUsuario.grupo_ativo == Const.PERFIL_COORDENADOR_GERAL"
                >
                    <Laudo :dados="getProjetosLaudoFinal"
                           :estado="Const.ESTADO_ANALISE_LAUDO"
                    ></Laudo>
                </v-tab-item>
                <v-tab-item
                    :value="'tab-1'"
                    :key="1"
                    v-if="getUsuario.grupo_ativo == Const.PERFIL_COORDENADOR_GERAL"
                >
                    <Laudo :dados="getProjetosLaudoAssinar"
                           :estado="Const.ESTADO_LAUDO_FINALIZADO"
                    ></Laudo>
                </v-tab-item>
                <v-tab-item
                    :value="'tab-2'"
                    :key="2"
                >
                    <Laudo :dados="getProjetosLaudoEmAssinatura"
                           :estado="Const.ESTADO_AGUARDANDO_ASSINATURA_LAUDO"
                    ></Laudo>
                </v-tab-item>
                <v-tab-item
                    :value="'tab-3'"
                    :key="3"
                    v-if="getUsuario.grupo_ativo == Const.PERFIL_COORDENADOR_GERAL"
                >
                    <Laudo :dados="getProjetosLaudoFinalizados"
                           :estado="Const.ESTADO_AVALIACAO_RESULTADOS_FINALIZADA"
                    ></Laudo>
                </v-tab-item>
            </v-tabs>
        </v-card>
    </v-container>
</template>

<script>

import { mapActions, mapGetters } from 'vuex';
import Const from '../../const';
import Laudo from './Laudo';

export default {
    name: 'PainelLaudo',
    data() {
        return {
            Const,
        };
    },
    created() {
        this.obterProjetosLaudoFinal({ estadoId: 10 });
        this.obterProjetosLaudoAssinar({ estadoId: 14 });
        this.obterProjetosLaudoEmAssinatura({ estadoId: 11 });
        this.obterProjetosLaudoFinalizados({ estadoId: 12 });
        this.obterDadosTabelaTecnico({ estadoId: 11, idAgente: this.getUsuario.usu_codigo });
    },
    components: {
        Laudo,
    },
    methods: {
        ...mapActions({
            obterDadosTabelaTecnico: 'avaliacaoResultados/obterDadosTabelaTecnico',
            obterProjetosLaudoFinal: 'avaliacaoResultados/obterProjetosLaudoFinal',
            obterProjetosLaudoAssinar: 'avaliacaoResultados/obterProjetosLaudoAssinar',
            obterProjetosLaudoEmAssinatura: 'avaliacaoResultados/obterProjetosLaudoEmAssinatura',
            obterProjetosLaudoFinalizados: 'avaliacaoResultados/obterProjetosLaudoFinalizados',
        }),
    },
    computed: {
        ...mapGetters({
            getProjetosLaudoFinal: 'avaliacaoResultados/getProjetosLaudoFinal',
            getProjetosLaudoAssinar: 'avaliacaoResultados/getProjetosLaudoAssinar',
            getProjetosLaudoEmAssinatura: 'avaliacaoResultados/getProjetosLaudoEmAssinatura',
            getProjetosLaudoFinalizados: 'avaliacaoResultados/getProjetosLaudoFinalizados',
            dadosTabelaTecnico: 'avaliacaoResultados/dadosTabelaTecnico',
            getUsuario: 'autenticacao/getUsuario',
            route: 'route',
        }),
    },
};
</script>
