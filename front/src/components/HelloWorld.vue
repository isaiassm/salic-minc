<template>
    <div class="principal">
       Esse é o componente inicial.
    </div>
</template>

<script>
    export default {
        name: 'TituloPagina',
        props: ['titulo'],
        methods: {
            voltar() {
                /* eslint-disable-next-line */
                window.history.length > 1 ? this.$router.go(-1) : this.$router.push('/');
            },
        },
    };
</script>
