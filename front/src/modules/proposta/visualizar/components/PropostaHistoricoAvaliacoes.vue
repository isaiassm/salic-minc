<template>
    <div class="tabelas">
            <div class="row">
                <slTabelaSimples v-bind:dados="dado"></slTabelaSimples>
            </div>
    </div>
</template>
<script>
import slTabelaSimples from '@/components/slTabelaSimples';

export default {
    name: 'PropostaHistoricoAvaliacoes',
    data() {
        return {
            dado: [],
        };
    },
    props: ['idpreprojeto'],
    components: {
        slTabelaSimples,
    },
    mounted() {
        if (typeof this.idpreprojeto !== 'undefined') {
            this.fetch(this.idpreprojeto);
        }
    },
    watch: {
        idpreprojeto(value) {
            this.fetch(value);
        },
    },
    methods: {
        fetch(id) {
            if (id) {
                const self = this;
                /* eslint-disable */
                $3.ajax({
                    url: '/proposta/visualizar/obter-historico-avaliacoes/idPreProjeto/' + id
                }).done(function (response) {
                    self.dado = response.data;
                });
            }
        },
    }
};
</script>