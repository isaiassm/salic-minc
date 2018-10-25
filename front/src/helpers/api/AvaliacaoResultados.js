import * as api from './base';

const buildData = (params) => {
    const bodyFormData = new FormData();

    Object.keys(params).forEach((key) => {
        bodyFormData.append(key, params[key]);
    });

    return bodyFormData;
};

const path = '/navegacao/menu-principal';

export const dadosMenu = () => api.getRequest(path);

export const criarRegistro = params => api.postRequest(path, buildData(params));

export const atualizarRegistro = params => api.putRequest(path, buildData(params), params.Codigo);

export const removerRegistro = params => api.deleteRequest(path, params.Codigo);

export const getTeste = params => api.postRequest('/realizarprestacaodecontas/carregar-destinatarios/', buildData(params));

export const getTipoAvaliacao = params => api.getRequest(`/avaliacao-resultados/tipo-avaliacao-rest/idPronac/${params}`);

export const obterDadosTabelaTecnico = (params) => {
    const data = params;
    return api.getRequest(`/avaliacao-resultados/fluxo-projeto?estadoid=${data.estadoid}&idAgente=${data.idAgente}`);
};

export const obterHistoricoEncaminhamento = params => api.getRequest(`/avaliacao-resultados/historico/idPronac/${params}`);

export const planilha = params => api.getRequest(`/prestacao-contas/realizar-prestacao-contas/planilha-analise-filtros/idPronac/${params}`);

export const projetoAnalise = params => api.getRequest(`/avaliacao-resultados/projeto/idPronac/${params}`);

export const consolidacaoAnalise = params => api.getRequest(`/prestacao-contas/visualizar-projeto/dados-projeto?idPronac=${params}`);

export const obterDestinatarios = () => api.getRequest('/avaliacao-resultados/tecnicos');

export const encaminharParaTecnico = params => api.postRequest('/avaliacao-resultados/estado/', buildData(params));

export const obterDadosItemComprovacao = params => api.getRequest(`/avaliacao-resultados/avaliacao-comprovante/${params}`);

export const criarParecerLaudoFinal = params => api.postRequest('/avaliacao-resultados/laudo', buildData(params));

export const finalizarParecerLaudoFinal = params => api.postRequest('/avaliacao-resultados/estado', buildData(params));

export const obterProjetosParaDistribuir = () => api.getRequest('/avaliacao-resultados/projeto-inicio');

export const criarDiligencia = params => api.postRequest('/diligencia/diligencia', buildData(params));

/**  PARECER TECNICO */

export const parecerConsolidacao = params => api.getRequest(`/avaliacao-resultados/emissao-parecer-rest/idPronac/${params}`);

export const criarParecer = (params) => {
    const parametro = params.idPronac;
    delete params.idPronac;
    const data = params;

    return api.postRequest(`/avaliacao-resultados/emissao-parecer-rest/idPronac/${parametro}`, buildData(data));
};

export const finalizarParecer = (params) => {
    // const parametro = params.idPronac;
    // delete params.idPronac;
    const data = params;

    return api.postRequest('/avaliacao-resultados/estado', buildData(data));
};

/** FIM DO PARECER TECNICO */


export const obterLaudoFinal = idPronac => api.getRequest(`/avaliacao-resultados/laudo/get?idPronac=${idPronac}`);

export const obterProjetosLaudoFinal = param => api.getRequest(`/avaliacao-resultados/laudo/index?estadoId=${param.estadoId}`);

export const alterarPerfil = (grupoAtivo, orgaoAtivo) => api.getRequest(`perfil/perfil-rest/index?codGrupo=${grupoAtivo}&codOrgao=${orgaoAtivo}`);

export const obterProjetosAssinatura = params => api.getRequest(`/avaliacao-resultados/projeto-assinatura/estado/${params.estado}`);

export const projetosRevisao = (params) => {
    const data = params;
    return api.getRequest(`/avaliacao-resultados/fluxo-projeto?estadoid=${data.estadoid}&idAgente=${data.idAgente}`);
};

export const buscarDetalhamentoItens = idPronac => api.getRequest(`/avaliacao-resultados/detalhamento-itens-rest?idPronac=${idPronac}`);

export const buscarComprovantes = (itemBuscaComprovantes) => {
    const modulo = '/prestacao-contas';
    const controller = '/comprovante-pagamento';

    const idPronac = `idPronac=${itemBuscaComprovantes.IdPRONAC}`;
    const idPlanilhaItem = `idPlanilhaItem=${itemBuscaComprovantes.idPlanilhaItens}`;
    const produto = `produto=${itemBuscaComprovantes.cdProduto}`;
    const uf = `uf=${itemBuscaComprovantes.Uf}`;
    const idMunicipio = `idmunicipio=${itemBuscaComprovantes.cdCidade}`;
    const etapa = `etapa=${itemBuscaComprovantes.cdEtapa}`;
    const stItemAvaliado = `stItemAvaliado=${itemBuscaComprovantes.stItemAvaliado}`;

    const url = `${modulo}${controller}`;
    const params = `?${idPronac}&${idPlanilhaItem}&${produto}&${uf}&${idMunicipio}&${stItemAvaliado}&${etapa}`;

    return api.getRequest(url + params);
};

export const devolverProjeto = params => api.postRequest(`/avaliacao-resultados/estado?idPronac=${params.idPronac}&atual=${params.atual}&proximo=${params.proximo}`);
