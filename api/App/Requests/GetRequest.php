<?php

class GetRequest implements RequestInterface
{
    public function getPath()
    {
        return $_SERVER['REQUEST_URI'];
    }

    public function getMethod()
    {
        return 'GET';
    }
}
