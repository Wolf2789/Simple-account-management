<?php namespace php\api;
class JsonRpc {
    const JSON_RPC_VERSION = '2.0';
    private $_server_url, $_server_object;

    public function __construct($URLorObject) {
        if (is_string($URLorObject)) {
            if (!$URLorObject)
                throw new \Exception('URL string can\'t be empty');
            $this->_server_url = $URLorObject;
        } elseif (is_object($URLorObject)) {
            $this->_server_object = $URLorObject;
        } else {
            throw new \Exception('Input parameter must be URL string or server class object');
        }
    }

    public function __call($method, array $params) {
        if (is_null($this->_server_url))
            throw new \Exception('This is server JSON-RPC object: you can\'t call remote methods');
        $request = new \stdClass();
        $request->jsonrpc = self::JSON_RPC_VERSION;
        $request->method = $method;
        $request->params = $params;
        $request->id = md5(uniqid(microtime(true), true));
        $request_json = json_encode($request);
        $ch = curl_init();
        curl_setopt_array($ch,
            array(CURLOPT_URL => $this->_server_url, CURLOPT_HEADER => 0, CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => $request_json, CURLOPT_RETURNTRANSFER => 1));
        $response_json = curl_exec($ch);
        if (curl_errno($ch))
            throw new \Exception(curl_error($ch), curl_errno($ch));
        if (curl_getinfo($ch, CURLINFO_HTTP_CODE) != 200)
            throw new \Exception(sprintf('Curl response http error code "%s"',
                curl_getinfo($ch, CURLINFO_HTTP_CODE)));
        curl_close($ch);
        $response = $this->_parseJson($response_json);
        $this->_checkResponse($response, $request);
        return $response->result;
    }

    public function process() {
        if (is_null($this->_server_object))
            throw new \Exception('This is client JSON-RPC object: you can\'t process request');
        ob_start();
        $request_json = file_get_contents('php://input');
        $response = new \stdClass();
        $response->jsonrpc = self::JSON_RPC_VERSION;
        try {
            $request = $this->_parseJson($request_json);
            $this->_checkRequest($request);
            $response->result = call_user_func_array(
                array($this->_server_object, $request->method), $request->params);
            $response->id = $request->id;
        } catch (\Exception $ex) {
            $response->error = new \stdClass();
            $response->error->code = $ex->getCode();
            $response->error->message = $ex->getMessage();
            $response->id = null;
        }
        ob_clean();
        echo json_encode($response);
    }

    private function _parseJson($data) {
        $json_data = json_decode($data, false, 32);
        if (is_null($json_data))
            throw new \Exception('Parse error', -32700);
        return $json_data;
    }

    private function _checkRequest($object) {
        if (!is_object($object) || !isset($object->jsonrpc) || $object->jsonrpc !== self::JSON_RPC_VERSION || !isset(
                $object->method) || !is_string($object->method) || !$object->method || (isset(
                    $object->params) && !is_array($object->params)) || !isset($object->id)
        )
            throw new \Exception('Invalid Request', -32600);
        if (!is_callable(array($this->_server_object, $object->method)))
            throw new \Exception('Method not found', -32601);
        if (is_null($object->params))
            $object->params = array();
    }

    private function _checkResponse($object, $request) {
        if (!is_object($object) || !isset($object->jsonrpc) || $object->jsonrpc !== self::JSON_RPC_VERSION || (!isset(
                    $object->result) && !isset($object->error)) || (isset($object->result) && (!isset(
                        $object->id) || $object->id !== $request->id)) || (isset($object->error) && (!is_object(
                        $object->error) || !isset($object->error->code) || !isset($object->error->message)))
        )
            throw new \Exception('Invalid Response', -32600);
        if (isset($object->error))
            throw new \Exception($object->error->message, $object->error->code);
    }
}
