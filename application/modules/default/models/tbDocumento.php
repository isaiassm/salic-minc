<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Vinculo
 *
 * @author tisomar
 */
class tbDocumento extends GenericModel {

    protected $_banco = "BDCORPORATIVO";
    protected $_schema = 'scCorp';
    protected $_name = "tbDocumento";

    
    /**
     * M�todo para cadastrar
     * @access public
     * @param array $dados
     * @return integer (retorna o �ltimo id cadastrado)
     */
    public function cadastrarDados($dados)
    {
            return $this->insert($dados);
    } // fecha m�todo cadastrarDados()

    
    /**
     * Grava registro. Se seja passado um ID ele altera um registro existente
     * @param array $dados - array com dados referentes as colunas da tabela no formato "nome_coluna_1"=>"valor_1","nome_coluna_2"=>"valor_2"
     * @return ID do registro inserido/alterado ou FALSE em caso de erro
     */
    public function ultimodocumento($where=array()) {

        $slct = $this->select();
        $slct->setIntegrityCheck(false);
        $slct->from($this);

        //adiciona quantos filtros foram enviados
        foreach ($where as $coluna => $valor) {
            $slct->where($coluna, $valor);
        }


        //adicionando linha order ao select
        $slct->order('idDocumento desc');
        $slct->limit('1', '0');

        //xd($slct->query());

        return $this->fetchRow($slct);
    }

    public function buscardocumentosrelatorio($idnrdocumento = null) {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(
                array('d' => $this->_schema . '.' . $this->_name),
                array(
                    'd.idDocumento',
                    'd.dsDocumento',
                    'd.nmTitulo'
                )
        );
        $select->joinInner(
                array('a' => 'tbArquivo'),
                'd.idArquivo = a.idArquivo',
                array(
                    'a.idArquivo',
                    'a.nmArquivo',
                    'a.dtEnvio',
                    'a.nrTamanho'
                ),
                'BDCORPORATIVO.sccorp'
        );
        $select->joinInner(
                array('td' => 'tbTipoDocumento'),
                'td.idTipoDocumento = d.idTipoDocumento',
                array(
                    'td.dsTipoDocumento'
                ),
                'SAC.dbo'
        );
        if ($idnrdocumento) {
            $select->where('d.idDocumento = ?', $idnrdocumento);
        }
//        xd($select->assemble());
        return $this->fetchAll($select);
    }
    
    public function excluir($where)
    {
        return $this->delete($where);
    }
}

?>
