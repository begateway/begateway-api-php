<?php

namespace BeGateway;

class ResponseApi extends ResponseBase
{
    public function isSuccess()
    {
        return isset($this->getResponse()->id);
    }

    public function getId()
    {
        if ($this->isSuccess()) {
            return $this->getResponse()->id;
        }
    }

    public function getMessage()
    {
        if (isset($this->getResponse()->message)) {
            return $this->getResponse()->message;
        } elseif ($this->isError()) {
            return $this->_compileErrors();
        } else {
            return '';
        }
    }

    private function _compileErrors()
    {
        $message = 'there are errors in request parameters.';
        if (isset($this->getResponse()->errors)) {
            foreach ($this->getResponse()->errors as $name => $desc) {
                $message .= ' ' . print_r($name, true);
                foreach ($desc as $value) {
                    $message .= ' ' . $value . '.';
                }
            }
        }

        return $message;
    }
}
