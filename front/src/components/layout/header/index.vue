<template>
    <div>
        <HeaderMenuPrincipalSidebar :dadosMenu="dadosMenu"></HeaderMenuPrincipalSidebar>
        <v-toolbar
            app
            dense
            dark
            fixed
            clipped-left
            color="primary"
        >
            <HeaderLogo></HeaderLogo>
            <v-toolbar-title class="ma-0 hidden-sm-and-down">Salic</v-toolbar-title>
            <v-spacer></v-spacer>
            <HeaderMenuPrincipalToolbar :dadosMenu="dadosMenu"></HeaderMenuPrincipalToolbar>
            <HeaderSolicitacoes></HeaderSolicitacoes>
            <HeaderInformacoesDaConta></HeaderInformacoesDaConta>
            <v-divider vertical class="hidden-md-and-up"></v-divider>
            <v-toolbar-side-icon
                class="hidden-md-and-up"
                v-if="$vuetify.breakpoint.smAndDown"
                @click.stop="drawerRight = !drawerRight"
            ></v-toolbar-side-icon>
        </v-toolbar>
    </div>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex';
    import HeaderMenuPrincipalToolbar from './HeaderMenuPrincipalToolbar';
    import HeaderMenuPrincipalSidebar from './HeaderMenuPrincipalSidebar';
    import HeaderInformacoesDaConta from './HeaderInformacoesDaConta';
    import HeaderSolicitacoes from './HeaderSolicitacoes';
    import HeaderLogo from './HeaderLogo';

    export default {
        name: 'Header',
        components: {
            HeaderInformacoesDaConta,
            HeaderMenuPrincipalToolbar,
            HeaderMenuPrincipalSidebar,
            HeaderSolicitacoes,
            HeaderLogo,
        },
        data() {
            return {
                drawerRight: false,
            };
        },
        created() {
            this.buscarDadosMenu();
            this.buscarDadosLayout();
        },
        watch: {
            statusSidebarDireita(value) {
                this.drawerRight = value;
            },
            drawerRight(value) {
                this.atualizarStatusSidebar(value);
            },
        },
        computed: {
            ...mapGetters({
                dadosMenu: 'layout/getDadosMenu',
                statusSidebarDireita: 'layout/getStatusSidebarDireita',
            }),
        },
        methods: {
            ...mapActions({
                buscarDadosMenu: 'layout/buscarDadosMenu',
                buscarDadosLayout: 'layout/buscarDadosLayout',
                atualizarStatusSidebar: 'layout/atualizarStatusSidebarDireita',
            }),
        },
    };
</script>
