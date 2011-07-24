<?php
/**
 * @class Response
 * A simple JSON Response class.
 */
class ExtResponse {
    public $success, $data, $message, $errors, $tid, $trace,$totalCount;

    public function __construct($params = array()) {
        $this->success  = isset($params["success"]) ? $params["success"] : false;
        $this->totalCount  = isset($params["totalCount"]) ? $params["totalCount"] : 0;
        $this->message  = isset($params["message"]) ? $params["message"] : '';
        $this->data     = isset($params["data"])    ? $params["data"]    : array();
    }

    public function to_json() {
        return json_encode(array(
            'success'   => $this->success,
            'message'   => $this->message,
            'totalCount'=> $this->totalCount,
            'data'      => $this->data
        ));
    }
}
