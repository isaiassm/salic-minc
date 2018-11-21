<?php

namespace Application\Modules\Execucao\Service\Fiscalizacao;


class Fiscalizacao implements \MinC\Servico\IServicoRestZend
{
    /**
     * @var \Zend_Controller_Request_Abstract $request
     */
    private $request;

    /**
     * @var \Zend_Controller_Response_Abstract $response
     */
    private $response;

    function __construct($request, $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function listaFiscalizacao()
    {
        $idPronac = $this->request->idPronac;
        // $idFiscalizacao = $this->request->idFiscalizacao;

        if (strlen($idPronac) > 7) {
            $idPronac = \Seguranca::dencrypt($idPronac);
        }

        $Projetos = new \Projetos();
        $dadosProj = $Projetos->buscar(array('IdPRONAC = ?' => $idPronac))->current();

        $infoProjeto = $Projetos->projetosFiscalizacaoConsultar(array('Projetos.IdPRONAC = ?' => $idPronac), array('tbFiscalizacao.dtInicioFiscalizacaoProjeto ASC', 'tbFiscalizacao.dtFimFiscalizacaoProjeto ASC'));

        $listaFiscalizacao = $this->montaListaFiscalizacao($infoProjeto);

        return $listaFiscalizacao;
    }

    public function visualizarFiscalizacao()
    {
        $idPronac = $this->request->idPronac;
        $idFiscalizacao = $this->request->id;

        if (strlen($idPronac) > 7) {
            $idPronac = \Seguranca::dencrypt($idPronac);
        }

        $Projetos = new \Projetos();
        $dadosProj = $Projetos->buscar(array('IdPRONAC = ?' => $idPronac))->current();

        $infoProjeto = $Projetos->projetosFiscalizacaoConsultar(array('Projetos.IdPRONAC = ?' => $idPronac, 'tbFiscalizacao.idFiscalizacao = ?' => $idFiscalizacao), array('tbFiscalizacao.dtInicioFiscalizacaoProjeto ASC', 'tbFiscalizacao.dtFimFiscalizacaoProjeto ASC'));
xd($infoProjeto);
        $Localizacoes = new \Proposta_Model_DbTable_Abrangencia();
        $dadosLocalizacoes = $Localizacoes->buscarRegiaoUFMunicipio($infoProjeto[0]['idProjeto']);
        // xd($dadosLocalizacoes);

        $OrgaoFiscalizadorDao = new \OrgaoFiscalizador();
        $ArquivoFiscalizacaoDao = new \ArquivoFiscalizacao();

        if ($idFiscalizacao) {
            $arquivos = $ArquivoFiscalizacaoDao->buscarArquivo(array('arqfis.idFiscalizacao = ?' => $idFiscalizacao));
            $dadosOrgaos = $OrgaoFiscalizadorDao->dadosOrgaos(array('tbOF.idFiscalizacao = ?' => $idFiscalizacao));
        }

        $RelatorioFiscalizacaoDAO = new \RelatorioFiscalizacao();
        $relatorioFiscalizacao = $RelatorioFiscalizacaoDAO->buscaRelatorioFiscalizacao($idFiscalizacao);

        $resultArray['locaisFiscalizacao'] = $this->montaLocaisFiscalizacao($dadosLocalizacoes);
        xd($resultArray['locaisFiscalizacao']);
        $resultArray['oficializarFiscalizacao'] = $this->montaOficializarFiscalizacao($infoProjeto);
        $resultArray['arquivosFiscalizacao'] = $this->montaArquivosFiscalizacao($arquivos);
        $resultArray['fiscalizacaoConcluidaParecer'] = $this->montaFiscalizacaoConcluidaParecer($relatorioFiscalizacao, $infoProjeto[0]['dtInicioFiscalizacaoProjeto']);

        return $resultArray;
    }

    private function montaListaFiscalizacao($dados)
    {
        foreach ($dados as $item) {
            $objDateTimeDtInicio = ' ';
            $objDateTimeDtFim = ' ';

            if (!empty($item['dtInicioFiscalizacaoProjeto'])) {
                $objDateTimeDtInicio = new \DateTime($item['dtInicioFiscalizacaoProjeto']);
                $objDateTimeDtInicio = $objDateTimeDtInicio->format('d/m/Y');
            }

            if (!empty($item['dtFimFiscalizacaoProjeto'])) {
                $objDateTimeDtFim = new \DateTime($item['dtFimFiscalizacaoProjeto']);
                $objDateTimeDtFim = $objDateTimeDtFim->format('d/m/Y');
            }

            $listaFiscalizacao[] = [
                'dtInicio' => $objDateTimeDtInicio,
                'dtFim' => $objDateTimeDtFim,
                'cpfTecnico' => $item['cpfTecnico'],
                'nmTecnico' => $item['nmTecnico'],
            ];

        }
        return $listaFiscalizacao;
    }

    private function montaLocaisFiscalizacao($dados)
    {
        foreach ($dados as $item) {
            $locaisFiscalizacao[] = [
                'regiao' => $item->Regiao,
                'uf' => $item->Descricao,
                'cidade' => $item->Municipio,
            ];

        }
        return $locaisFiscalizacao;
    }

    private function montaOficializarFiscalizacao($dados)
    {
        foreach ($dados as $item) {
            $objDateTimeDtInicio = ' ';
            $objDateTimeDtFim = ' ';
            $objDateTimeDtResposta = ' ';

            if (!empty($item['dtInicioFiscalizacaoProjeto'])) {
                $objDateTimeDtInicio = new \DateTime($item['dtInicioFiscalizacaoProjeto']);
                $objDateTimeDtInicio = $objDateTimeDtInicio->format('d/m/Y');
            }

            if (!empty($item['dtFimFiscalizacaoProjeto'])) {
                $objDateTimeDtFim = new \DateTime($item['dtFimFiscalizacaoProjeto']);
                $objDateTimeDtFim = $objDateTimeDtFim->format('d/m/Y');
            }
            if (!empty($item['dtRespostaSolicitada'])) {
                $objDateTimeDtResposta = new \DateTime($item['dtRespostaSolicitada']);
                $objDateTimeDtResposta = $objDateTimeDtResposta->format('d/m/Y');
            }

            $listaFiscalizacao[] = [
                'dtInicio' => $objDateTimeDtInicio,
                'dtFim' => $objDateTimeDtFim,
                'dtResposta' => $objDateTimeDtResposta,
                'cpfTecnico' => $item['cpfTecnico'],
                'nmTecnico' => $item['nmTecnico'],
                'tpDemandante' => $item['tpDemandante'],
                'dsFiscalizacaoProjeto' => $item['dsFiscalizacaoProjeto'],
            ];

        }
        return $listaFiscalizacao;
    }

    private function montaArquivosFiscalizacao($dados)
    {
        foreach ($dados as $item) {
            $arquivosFiscalizacao[] = [
                'nmArquivo' => $item['nmArquivo'],
                'idArquivo' => $item['idArquivo'],
            ];

        }
        return $arquivosFiscalizacao;
    }

    private function montaFiscalizacaoConcluidaParecer($dados, $dtInicioFiscalizacao)
    {
        $dtDeCorte = strtotime(date('2013-09-15 00:00:00'));
        $result['resumoExecucao'] = [
            'dsAcoesProgramadas' => $dados['dsAcoesProgramadas'],
            'dsAcoesExecutadas' => $dados['dsAcoesExecutadas'],
            'dsBeneficioAlcancado' => $dados['dsBeneficioAlcancado'],
            'dsDificuldadeEncontrada' => $dados['dsDificuldadeEncontrada'],
            ];

        if ($dtInicioFiscalizacao < $dtDeCorte) {

            $result['stConvenioFiscalizacao'] = [
                'stSiafi' => $this->statusConvenio($dados['stSiafi']),
                'stPrestacaoContas' => $this->statusFiscalizacao($dados['stPrestacaoContas']),
                'stCumpridasNormas' => $this->statusFiscalizacao($dados['stCumpridasNormas']),
                'stCumpridoPrazo' => $this->statusFiscalizacao($dados['stCumpridoPrazo']),
            ];
        }

        $result['utilizacaoRecursos'] = [
            'stApuracaoUFiscalizacao' => $this->statusFiscalizacao($dados['stApuracaoUFiscalizacao']),
            'stComprovacaoUtilizacaoRecurso' => $this->statusFiscalizacao($dados['stComprovacaoUtilizacaoRecurso']),
            'stCompatibilidadeDesembolsoEvo' => $this->statusFiscalizacao($dados['stCompatibilidadeDesembolsoEvo']),
            'stOcorreuDespesas' => $this->statusFiscalizacao($dados['stOcorreuDespesas']),
            'stPagamentoServidorPublico' => $this->statusFiscalizacao($dados['stPagamentoServidorPublico']),
            'stDespesaAdministracao' => $this->statusFiscalizacao($dados['stDespesaAdministracao']),
            'stTransferenciaRecurso' => $this->statusFiscalizacao($dados['stTransferenciaRecurso']),
            'stDespesasPublicidade' => $this->statusFiscalizacao($dados['stDespesasPublicidade']),
            'stOcorreuAditamento' => $this->statusFiscalizacao($dados['stOcorreuAditamento']),
            'stAplicadosRecursos' => $this->statusFiscalizacao($dados['stAplicadosRecursos']),
            'stRecursosCaptados' => $this->statusFiscalizacao($dados['stRecursosCaptados']),
            'stSaldoAposEncerramento' => $this->statusFiscalizacao($dados['stSaldoAposEncerramento']),
            'stSaldoVerificacaoFNC' => $this->statusFiscalizacao($dados['stSaldoVerificacaoFNC']),
        ];

        $result['comprovantesDespesa'] = [
            'stProcessoDocumentado' => $this->statusFiscalizacao($dados['stProcessoDocumentado']),
            'stDocumentacaoCompleta' => $this->statusFiscalizacao($dados['stDocumentacaoCompleta']),
            'stConformidadeExecucao' => $this->statusFiscalizacao($dados['stConformidadeExecucao']),
            'stIdentificaProjeto' => $this->statusFiscalizacao($dados['stIdentificaProjeto']),
            'stDespesaAnterior' => $this->statusFiscalizacao($dados['stDespesaAnterior']),
            'stDespesaPosterior' => $this->statusFiscalizacao($dados['stDespesaPosterior']),
            'stDespesaCoincidem' => $this->statusFiscalizacao($dados['stDespesaCoincidem']),
            'stDespesaRelacionada' => $this->statusFiscalizacao($dados['stDespesaRelacionada']),
            'stComprovanteFiscal' => $this->statusFiscalizacao($dados['stComprovanteFiscal']),
            ];

        $result['divulgacao'] = [
            'stCienciaLegislativo' => $this->statusFiscalizacao($dados['stCienciaLegislativo']),
            'stExigenciaLegal' => $this->statusFiscalizacao($dados['stExigenciaLegal']),
            'stMaterialInformativo' => $this->statusFiscalizacao($dados['stMaterialInformativo']),
            ];

        $result['execucao'] = [
            'stFinalidadeEsperada' => $this->statusFiscalizacao($dados['stFinalidadeEsperada']),
            'stPlanoTrabalho' => $this->statusFiscalizacao($dados['stPlanoTrabalho']),
            'stExecucaoAprovado' => $this->statusFiscalizacao($dados['stExecucaoAprovado']),
            'dsObservacao' => $this->statusFiscalizacao($dados['dsObservacao']),
            ];

        $result['empregosGeradosProjeto'] = [
            'qtEmpregoDireto' => $dados['qtEmpregoDireto'],
            'qtEmpregoIndireto' => $dados['qtEmpregoIndireto'],
            'qtEmpregoTotal' => $dados['qtEmpregoDireto'] + $dados['qtEmpregoIndireto'],
            'dsEvidencia' => $dados['dsEvidencia'],
            'dsRecomendacaoEquipe' => $dados['dsRecomendacaoEquipe'],
            'dsConclusaoEquipe' => $dados['dsConclusaoEquipe'],
            'dsParecerTecnico' => $dados['dsParecerTecnico'],
            'dsParecer' => $dados['dsParecer'],
            ];

        return $result;
    }

    private function statusFiscalizacao($dado)
    {
        switch ($dado) {
            case 1:
                $result = 'Sim';
                break;
            case 2:
                $result = 'N&atilde;o';
                break;
            case 3:
                $result = 'N&atilde;o se aplica.';
                break;
            default:
                $result = ' - ';
        }

        return $result;
    }

    private function statusConvenio($dado)
    {
        switch ($dado) {
            case 1:
                $result = 'Aprovado';
                break;
            case 2:
                $result = 'A aprovar';
                break;
            case 3:
                $result = 'A comprovar';
                break;
            default:
                $result = ' - ';
        }

        return $result;
    }
}
