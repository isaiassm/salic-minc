<?php

use Application\Modules\Projeto\Service\Diligencia\DiligenciaProposta as DiligenciaPropostaService;

class Projeto_DiligenciaPropostaRestController extends MinC_Controller_Rest_Abstract
{
    protected $idAgente = 0;

    public function __construct(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = array())
    {
        $this->setValidateUserIsLogged();

        parent::__construct($request, $response, $invokeArgs);
    }

    public function indexAction()
    {

    }

    public function getAction()
    {
        try {
            $diligenciaService = new DiligenciaPropostaService($this->getRequest(), $this->getResponse());
            $resposta = $diligenciaService->visualizarDiligenciaProposta();

            $this->renderJsonResponse($resposta, 200);

        } catch (Exception $objException) {
            $this->customRenderJsonResponse([
                'error' => [
                    'code' => 404,
                    'message' => $objException->getMessage()
                ]
            ], 404);

        }
    }

    public function postAction()
    {
        $this->renderJsonResponse([], 201);
    }

    public function putAction()
    {
        $this->renderJsonResponse([], 200);
    }

    public function deleteAction()
    {
        $this->renderJsonResponse([], 204);
    }

    public function headAction()
    {
        $this->renderJsonResponse([], 200);
    }
}
