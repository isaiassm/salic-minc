<?php

namespace Application\Modules\Readequacao\Service\Assinatura\Acao;

use MinC\Assinatura\Acao\IAcaoAssinar;

class Assinar implements IAcaoAssinar
{
    public function executar(\MinC\Assinatura\Model\Assinatura $assinatura)
    {
        $idTipoDoAtoAdministrativo = $assinatura->modeloTbDocumentoAssinatura->getIdTipoDoAtoAdministrativo();
        $documentoAssinaturaDbTable = new \Assinatura_Model_DbTable_TbDocumentoAssinatura();
        
        $documentoAssinatura = $documentoAssinaturaDbTable->findBy(
            [
                'IdPRONAC' => $assinatura->modeloTbAssinatura->getIdPronac(),
                'idTipoDoAtoAdministrativo' => (int)$assinatura->modeloTbAtoAdministrativo->getIdTipoDoAto(),
                'cdSituacao' => (int)\Assinatura_Model_TbDocumentoAssinatura::CD_SITUACAO_DISPONIVEL_PARA_ASSINATURA,
                'stEstado = ?' => (int)\Assinatura_Model_TbDocumentoAssinatura::ST_ESTADO_DOCUMENTO_ATIVO
            ]
        );
        
        if (empty($documentoAssinatura)) {
            return;
        }
            
        $tbReadequacaoXParecerDbTable = new \Readequacao_Model_DbTable_TbReadequacaoXParecer();
        $tbReadequacaoXParecer = $tbReadequacaoXParecerDbTable->findBy([
            'idParecer' => $documentoAssinatura['idAtoDeGestao']
        ]);
        $idReadequacao = $tbReadequacaoXParecer['idReadequacao'];        
        $atoAdministrativo = $assinatura->modeloTbAtoAdministrativo;
        
        switch ($atoAdministrativo->getIdPerfilDoAssinante()) {
            case \Autenticacao_Model_Grupos::COORDENADOR_ACOMPANHAMENTO:
                $siEncaminhamento = \Readequacao_Model_tbTipoEncaminhamento::SI_ENCAMINHAMENTO_SOLICITACAO_ENCAMINHADA_AO_COORDENADOR_GERAL;
                break;
            case (string)\Autenticacao_Model_Grupos::PARECERISTA:
                $siEncaminhamento = (int)\Readequacao_Model_tbTipoEncaminhamento::SI_ENCAMINHAMENTO_DEVOLVIDO_ANALISE_TECNICA;
                break;
            case (string)\Autenticacao_Model_Grupos::COORDENADOR_DE_PARECER:
                $siEncaminhamento = (int)\Readequacao_Model_tbTipoEncaminhamento::SI_ENCAMINHAMENTO_SOLICITACAO_ENCAMINHADA_AO_PRESIDENTE_DA_VINCULADA;
                break;
            case (string)\Autenticacao_Model_Grupos::TECNICO_ACOMPANHAMENTO:
                
                $tbDistribuirReadequacao = new \Readequacao_Model_tbDistribuirReadequacao();
                $dDP = $tbDistribuirReadequacao->buscar(array('idReadequacao = ?' => $tbReadequacaoXParecer['idReadequacao']));
                
                if (count($dDP) > 0) {
                    $dadosDP = [];
                    $dadosDP['DtRetornoAvaliador'] = new \Zend_Db_Expr('GETDATE()');
                    $whereDP = "idDistribuirReadequacao = " . $dDP[0]->idDistribuirReadequacao;
                    $x = $tbDistribuirReadequacao->update($dadosDP, $whereDP);
                }
                
                $siEncaminhamento = \Readequacao_Model_tbTipoEncaminhamento::SI_ENCAMINHAMENTO_DEVOLVIDA_COORDENADOR_TECNICO;
                break;
            case (string)\Autenticacao_Model_Grupos::COORDENADOR_GERAL_ACOMPANHAMENTO:
                $siEncaminhamento = \Readequacao_Model_tbTipoEncaminhamento::SI_ENCAMINHAMENTO_SOLICITACAO_ENCAMINHADA_AO_DIRETOR;
                break;
            case (string)\Autenticacao_Model_Grupos::PRESIDENTE_DE_VINCULADA:
                $siEncaminhamento = (int)\Readequacao_Model_tbTipoEncaminhamento::SI_ENCAMINHAMENTO_SOLICITACAO_ENCAMINHADA_AO_DIRETOR;
                break;
            case (string)\Autenticacao_Model_Grupos::COORDENADOR_GERAL_ACOMPANHAMENTO_PRESTACAO_DE_CONTAS:
                $siEncaminhamento = (int)\Readequacao_Model_tbTipoEncaminhamento::SI_ENCAMINHAMENTO_SOLICITACAO_ENCAMINHADA_AO_DIRETOR;
                break;
        }
        
        if ($siEncaminhamento) {
            $dados = ['siEncaminhamento' => $siEncaminhamento];
            $where = "idReadequacao = {$idReadequacao}";
            $tbReadequacao = new \Readequacao_Model_DbTable_TbReadequacao();
            $tbReadequacao->update($dados, $where);
        }
    }
}
