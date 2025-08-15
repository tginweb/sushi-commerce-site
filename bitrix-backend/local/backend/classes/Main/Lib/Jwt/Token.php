<?php

namespace Main\Lib\Jwt;

abstract class Token
{
    var $tokenString = '';
    var $jwt;
    var $decodeErrorCode;
    var $decodedPayload;
    var $actualPayload = [];

    public function __construct($tokenString = null, $actualPayload = [])
    {
        if ($tokenString)
            $this->setTokenString($tokenString);

        if ($actualPayload)
            $this->setActualPayload($actualPayload);
    }

    public function getActualTokenString()
    {
        if ($this->isValid()) {
            return $this->getTokenString();
        } else {
            return $this->generate();
        }
    }

    public function isValid()
    {
        $this->getDecodedPayload();
        return !$this->decodeErrorCode;
    }

    public function getDecodedPayload()
    {
        if (!isset($this->decodedPayload)) {
            $this->decodedPayload = $this->decode();
        }
    }

    public function decode()
    {
        $jwt = $this->getJwt();
        $this->decodeErrorCode = null;
        try {
            return $jwt->decode($this->tokenString);
        } catch (JWTException $exception) {
            $this->decodeErrorCode = $exception->getCode();
            return false;
        }
    }

    /**
     * @return JWT
     */
    abstract public function getJwt();

    public function getTokenString()
    {
        return $this->tokenString;
    }

    public function setTokenString($tokenString)
    {
        $this->tokenString = $tokenString;
        $this->decodeErrorCode = null;
    }

    public function generate()
    {
        $jwt = $this->getJwt();
        $tokenString = $jwt->encode($this->getActualPayload());
        $this->setTokenString($tokenString);
        return $tokenString;
    }

    public function getActualPayload()
    {
        return $this->actualPayload;
    }

    public function setActualPayload($actualPayload)
    {
        $this->actualPayload = $actualPayload;
        return $this;
    }
}
