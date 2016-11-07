<?php

namespace PhpSigep\Services\Real;

use PhpSigep\Services\Result;

/**
 * Class VerificarStatusCartaoPostagem
 * @package PhpSigep\Services\Real
 * @author davidalves1
 */
class VerificarStatusCartaoPostagem
{
    public function execute($numeroCartaoPostagem, $usuario, $senha)
    {
        $soapArgs = array(
            'numeroCartaoPostagem' => $numeroCartaoPostagem,
            'usuario' => $usuario,
            'senha' => $senha
        );

        $r = SoapClientFactory::getSoapClient()->getStatusCartaoPostagem($soapArgs);

        $errorCode = null;
        $errorMsg = null;
        $result = new Result();
        if (!$r) {
            $errorCode = 0;
        } else if ($r instanceof \SoapFault) {
            $errorCode = $r->getCode();
            $errorMsg = SoapClientFactory::convertEncoding($r->getMessage());
            $result->setSoapFault($r);
        } else if ($r instanceof \stdClass && property_exists($r, 'return')) {
            $result->setResult($r->return);
        } else {
            $errorCode = 0;
            $errorMsg = "A resposta do Correios não está no formato esperado.";
        }

        $result->setErrorCode($errorCode);
        $result->setErrorMsg($errorMsg);

        return $result;
    }
}