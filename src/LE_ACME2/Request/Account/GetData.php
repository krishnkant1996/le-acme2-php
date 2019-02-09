<?php

namespace LE_ACME2\Request\Account;

use LE_ACME2\Response;

use LE_ACME2\Exception;

class GetData extends AbstractLocation {
    
    protected function _getPayload() {
        
        return [];
    }

    /**
     * @return Response\AbstractResponse|Response\Account\GetData
     * @throws Exception\InvalidResponse
     * @throws Exception\RateLimitReached
     */
    public function getResponse() {
        
        return new Response\Account\GetData($this->_getRawResponse());
    }
}