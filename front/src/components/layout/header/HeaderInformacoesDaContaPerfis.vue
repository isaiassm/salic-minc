<template>
    <v-list>
        <v-list-tile v-if="Object.keys(perfis).length > 0">
            <v-list-tile-action>
                <v-icon color="indigo">person</v-icon>
            </v-list-tile-action>
            <v-list-tile-content>
                <v-menu>
                    <v-list-tile-title
                        slot="activator"
                    >
                        <span>{{perfilAtual.orgao_sigla_autorizada}} - {{perfilAtual.nome_grupo}}</span>
                        <v-icon>arrow_drop_down</v-icon>
                    </v-list-tile-title>
                    <v-list style="width: 440px; max-height: 500px; overflow: scroll;">
                        <v-list-tile v-for="(perfil, index) in perfis" :key="index">
                            <div v-if="perfil.orgao_sigla_autorizada" @click="trocarPerfil(perfil)"
                                 style="cursor:pointer;">
                                {{perfil.orgao_sigla_autorizada}} - {{perfil.nome_grupo}}
                            </div>
                            <div v-else @click="trocarPerfil(perfil)" style="cursor:pointer;">
                                {{perfil.nome_grupo}}
                            </div>
                        </v-list-tile>
                    </v-list>
                </v-menu>
            </v-list-tile-content>
        </v-list-tile>
        <v-list-tile v-else>
            <v-list-tile-action>
                <v-progress-circular
                    :width="3"
                    color="red"
                    indeterminate
                ></v-progress-circular>
            </v-list-tile-action>
            <v-list-tile-content>
                <div>Carregando perfis ...</div>
            </v-list-tile-content>
        </v-list-tile>
    </v-list>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex';

    export default {
        name: 'HeaderInformacoesDaContaPerfis',
        data() {
            return {
                loadingPerfis: true,
            };
        },
        created() {
            this.buscarPerfisDisponiveis();
        },
        computed: {
            ...mapGetters({
                perfis: 'layout/perfisDisponiveis',
                usuario: 'autenticacao/getUsuario',
            }),
            perfilAtual() {
                if (!this.usuario || !this.perfis) {
                    return {};
                }
                const grupoAtivo = this.usuario.grupo_ativo;
                const orgaoAtivo = this.usuario.orgao_ativo;
                const perfil = this.perfis.map((perfil) => {
                    if (parseInt(perfil.gru_codigo, 10) === parseInt(grupoAtivo, 10)
                        && parseInt(perfil.uog_orgao, 10) === parseInt(orgaoAtivo, 10)) {
                        return perfil;
                    }
                    return false;
                }).filter(value => (value !== false))[0];
                this.loadingPerfis = false;
                return perfil || {};
            },
        },
        methods: {
            ...mapActions({
                buscarPerfisDisponiveis: 'layout/buscarPerfisDisponiveis',
                alterarPerfil: 'layout/alterarPerfil',
            }),
            trocarPerfil(perfil) {
                this.alterarPerfil(perfil);
            },
        },
    };
</script>
