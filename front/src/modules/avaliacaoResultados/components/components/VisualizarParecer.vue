<template>
            <v-dialog v-model="dialog" fullscreen transition="dialog-bottom-transition">

                <v-tooltip v-if="typeof obj.dsParecer === 'undefined'" slot="activator" bottom>
                    <v-btn slot="activator" flat icon round @click.native="sincState(idPronac)">
                        <v-icon :color="statusButton(obj).color" :change="statusButton(obj).color" class="material-icons">{{ statusButton(obj).icon }}</v-icon>
                    </v-btn>
                    <span>{{ statusButton(obj).texto }}</span>
                </v-tooltip>
                <v-btn v-else slot="activator" round dark :color="statusButton(obj).color" @click.native="sincState(idPronac)">
                    <v-icon class="material-icons">{{ statusButton(obj).icon }}</v-icon>
                    <span>&nbsp;{{ statusButton(obj).texto }}</span>
                </v-btn>

                <v-card>
                    <v-toolbar dark color="green">
                        <v-btn icon dark @click.native="dialog = false">
                            <v-icon>close</v-icon>
                        </v-btn>
                        <v-toolbar-title>Avaliação de Resultados - Visualizar Parecer</v-toolbar-title>
                    </v-toolbar>
                    <v-container grid-list-sm>
                        <v-layout row wrap>
                            <v-flex xs12 sm12 md12>
                                <p><b>Projeto:</b> {{projeto.AnoProjeto}}{{projeto.Sequencial}} -- {{projeto.NomeProjeto}}</p>
                            </v-flex>
                            <v-flex xs12 sm12 md12 v-if="proponente.CgcCpf || proponente.Nome">
                                <p><b>Proponente:</b> {{proponente.CgcCpf | cnpjFilter}} -- {{proponente.Nome}}</p>
                            </v-flex>
                        </v-layout>
                        <v-divider></v-divider>
                    </v-container>
                    <h2 class="text-sm-center">Parecer de avaliação do cumprimento do objeto</h2>
                    <v-container grid-list-sm>
                        <v-layout wrap align-center>
                            <v-flex xs12 sm12 md12>
                                <div>
                                    <p><b>Manifestação: </b>{{parecerObjeto.dsManifestacaoObjeto}}</p>
                                </div>
                            </v-flex>
                            <v-flex xs12 sm12 md12>
                                <div>
                                    <h4>Parecer: </h4>
                                    <p v-html="parecerObjeto.dsParecerDeCumprimentoDoObjeto"></p>
                                </div>
                            </v-flex>
                        </v-layout>
                        <v-divider></v-divider>
                    </v-container>
                    <div v-if="parecerTecnico !== undefined && parecerTecnico.idPronac === idPronac">
                        <h2 class="text-sm-center">Parecer técnico de avaliação financeira</h2>
                        <v-container grid-list-sm>
                            <v-layout wrap align-center>
                                <v-flex xs12 sm12 md12>
                                    <div>
                                        <p v-if="parecerTecnico.siManifestacao == 'A'"><b>Manifestação: </b> Aprovado</p>
                                        <p v-else-if="parecerTecnico.siManifestacao == 'P'"><b>Manifestação: </b> Aprovado com ressalva</p>
                                        <p v-else-if="parecerTecnico.siManifestacao == 'R'"><b>Manifestação: </b> Reprovado</p>
                                    </div>
                                </v-flex>
                                <v-flex xs12 sm12 md12>
                                    <div>
                                        <h4>Parecer: </h4>
                                        <p v-html="parecerTecnico.dsParecer"></p>
                                    </div>
                                </v-flex>
                            </v-layout>
                            <v-divider></v-divider>
                        </v-container>
                    </div>
                </v-card>
            </v-dialog>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex';
    import cnpjFilter from '@/filters/cnpj';

    export default {
        name: 'VisualizarParecer',
        props: ['idPronac', 'obj'],
        data() {
            return {
                dialog: false,
            };
        },
        computed: {
            ...mapGetters({
                proponente: 'avaliacaoResultados/proponente',
                parecerTecnico: 'avaliacaoResultados/parecer',
                parecerObjeto: 'avaliacaoResultados/objetoParecer',
                projeto: 'avaliacaoResultados/projeto',
            }),
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
            statusButton(obj) {
                let status = {};

                if (typeof obj.dsParecer === 'undefined') {
                    status = {
                        color: '',
                        icon: 'filter_frames',
                        texto: 'Visualizar Objeto',
                    };
                } else if (typeof obj.dsParecer !== 'undefined' && obj.siManifestacao === 'A') {
                    status = {
                        color: 'green darken-4',
                        icon: 'mood',
                        texto: 'Aprovado',
                    };
                } else if (typeof obj.dsParecer !== 'undefined' && obj.siManifestacao === 'P') {
                    status = {
                        color: 'green lighten-1',
                        icon: 'sentiment_satisfied_alt',
                        texto: 'Aprovado com ressalva',
                    };
                } else if (typeof obj.dsParecer !== 'undefined' && obj.siManifestacao === 'R') {
                    status = {
                        color: 'red',
                        icon: 'sentiment_very_dissatisfied',
                        texto: 'Reprovado',
                    };
                }
                return status;
            },
        },
        filters: {
            cnpjFilter,
        },
    };
</script>
