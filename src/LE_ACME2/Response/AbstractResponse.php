<?php

namespace LE_ACME2\Response;

use LE_ACME2\Exception;

use LE_ACME2\Connector\Struct\RawResponse;

abstract class AbstractResponse {

    protected $_raw = NULL;

    protected $_pattern_header_location = '/^Location: (\S+)$/i';

    /**
     * AbstractResponse constructor.
     *
     * @param RawResponse $raw
     * @throws Exception\InvalidResponse
     * @throws Exception\RateLimitReached
     */
    public function __construct(RawResponse $raw) {

        $this->_raw = $raw;

        if($this->_isRateLimitReached()) {
            throw new Exception\RateLimitReached();
        }

        $result = $this->_isValid();
        if(!$result) {
            throw new Exception\InvalidResponse($raw);
        }
    }

    protected function _preg_match_headerLine(string $pattern) : ?array {

        foreach($this->_raw->header as $line) {

            if(preg_match($pattern, $line, $matches) === 1)
                return $matches;
        }
        return null;
    }

    protected function _isRateLimitReached() : bool {
        return $this->_preg_match_headerLine('/^HTTP.* 429 .*$/i') !== null;
    }

    protected function _isValid() : bool {

        return $this->_preg_match_headerLine('/^HTTP.* 201 Created$/i') !== null ||
            $this->_preg_match_headerLine('/^HTTP.* 200 OK$/i') !== null ||
            $this->_preg_match_headerLine('/^HTTP.* 204 .*$/i') !== null;
    }

    public function getRaw() : RawResponse {
        return $this->_raw;
    }
}