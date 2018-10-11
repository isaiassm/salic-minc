<?php

namespace Application\Modules\Readequacao\Service\Assinatura\Acao;

use MinC\Assinatura\Acao\IAcaoFinalizar;

class Finalizar implements IAcaoFinalizar
{
    public function executar(\MinC\Assinatura\Model\Assinatura $assinatura)
    {
        $tbReadequacaoXParecerDbTable = new \Readequacao_Model_DbTable_TbReadequacaoXParecer();		
        $tbReadequacaoXParecer = $tbReadequacaoXParecerDbTable->findBy([
            'idParecer' => $assinatura->modeloTbDocumentoAssinatura->getIdAtoDeGestao()
        ]);

        $idReadequacao = $tbReadequacaoXParecer['idReadequacao'];

        $dados = [
            'stEstado' => (int)\Readequacao_Model_DbTable_TbReadequacao::ST_ESTADO_FINALIZADO,
            'siEncaminhamento' => (int)\Readequacao_Model_tbTipoEncaminhamento::SI_ENCAMINHAMENTO_CHECKLIST_PUBLICACAO
        ];
        
        $where = "idReadequacao = {$idReadequacao}";	
        $tbReadequacao = new \Readequacao_Model_DbTable_TbReadequacao();
        $tbReadequacao->update($dados, $where);       
    }
}
