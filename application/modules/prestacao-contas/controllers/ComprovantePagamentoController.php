<?php

class PrestacaoContas_ComprovantePagamentoController extends Zend_Rest_Controller
{
    public function init()
    {
        $this->_helper->getHelper('contextSwitch')
            ->addActionContext('index', 'json')
            ->addActionContext('post', 'json')
            ->initContext('json');
    }

    public function headAction(){}

    public function indexAction()
    {
        $idPronac = $this->_request->getParam("idPronac");
        $idPlanilhaItem = $this->_request->getParam("idPlanilhaItem");
        $codigoProduto = $this->getRequest()->getParam('produto');
        $stItemAvaliado = $this->getRequest()->getParam('stItemAvaliado');

        $vwComprovacoes = new PrestacaoContas_Model_vwComprovacaoFinanceiraProjetoPorItemOrcamentario();
        $comprovantes = $vwComprovacoes->comprovacoes(
            $idPronac,
            $idPlanilhaItem,
            $stItemAvaliado,
            $codigoProduto
        );

        $data = [];

        foreach($comprovantes->toArray() as $key => $value){
            $data[] =  array_map('utf8_encode', $value);
        }

        $this->view->assign('data', $data);
        $this->getResponse()->setHttpResponseCode(200);
    }

    public function getAction()
    {}

    public function postAction()
    {
        $idPronac = $this->getRequest()->getParam('idpronac');
        $observacao = $this->getRequest()->getParam('observacao');
        $situacao = $this->getRequest()->getParam('situacao');
        $idComprovantePagamento = $this->getRequest()->getParam('idcomprovantepagamento');

        if (!$idPronac) {
            throw new Exception('Falta pronac');
        }

        /* $this->view->assign('data',['message' => 'Existe dilig&ecirc;ncia aguardando resposta!']); */
        /* $this->getResponse()->setHttpResponseCode(405); */

        /* $this->view->assign('data',['message' => 'criado!']); */
        /* $this->getResponse()->setHttpResponseCode(201); */

        $tblComprovantePag = new ComprovantePagamentoxPlanilhaAprovacao();
        $rsComprovantePag = $tblComprovantePag
            ->buscar( [ 'idComprovantePagamento = ?' => $idComprovantePagamento] )
            ->current();
        /* echo '<pre>'; */
        /* var_dump($idComprovantePagamento, $rsComprovantePag);die; */

        $rsComprovantePag->dtValidacao = date('Y/m/d H:i:s');
        $rsComprovantePag->dsJustificativa = $comprovantePagamento['observacao'];
        $rsComprovantePag->stItemAvaliado = $situacao;

        try {
            $rsComprovantePag->save();
            $this->view->assign('data',['message' => 'criado!']);
            $this->getResponse()->setHttpResponseCode(200);
        } catch (Exception $e) {
            $tblComprovantePag->getAdapter()->rollBack();
        }

    }

    public function putAction()
    {
         $this->getResponse()->setHttpResponseCode(400);
    }

    public function deleteAction()
    {}
}
