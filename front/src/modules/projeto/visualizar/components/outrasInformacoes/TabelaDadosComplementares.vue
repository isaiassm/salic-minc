<template>
    <v-expansion-panel popout focusable>
        <v-expansion-panel-content class="elevation-1">
            <v-layout slot="header" class="primary--text">
                <v-icon class="mr-3 primary--text">subject</v-icon>
                <span v-html="dadoComplementar">{{ dadoComplementar }}</span>
            </v-layout>
            <v-card>
                <v-card-text>
                    <div v-if="dsDadoComplementar && dsDadoComplementar.length > 1" v-html="dsDadoComplementar">
                        <td>{{ dsDadoComplementar }}</td>
                    </div>
                    <div v-else-if="custosVinculados">
                        <v-container grid-list-md>
                            <v-layout justify-space-around row wrap>
                                <v-flex xs6>
                                    <b>ITEM</b>
                                </v-flex>
                                <v-flex xs2>
                                    <b>PERCENTUAL</b>
                                </v-flex>
                            </v-layout>
                            <v-layout justify-space-around align-center row
                                      v-for="(custosVinculado, index) in custosVinculados" :key="index"
                            >
                                <v-flex xs6>
                                    <td>{{ custosVinculado.Descricao }}</td>
                                </v-flex>
                                <v-flex xs2>
                                    <td>{{ custosVinculado.Percentual }}</td>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </div>
                    <div v-else>
                        <v-data-table
                                no-data-text="Nenhum dado encontrado"
                                hide-actions
                                hide-headers
                        >
                        </v-data-table>
                    </div>
                </v-card-text>
            </v-card>
        </v-expansion-panel-content>
    </v-expansion-panel>
</template>

<script>
    export default {
        name: 'TabelaDadosComplementares',
        props: ['dadoComplementar', 'custosVinculados', 'dsDadoComplementar'],
    };
</script>

