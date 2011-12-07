<?php

class Ios_push extends CI_Controller {

    var $_data;

    public function __construct()
    {
        parent::__construct();

    }

    public function index()
    {
        $deviceTokens = array(
            'df1f60d6052d7061f52d9f9e0f618d41f0a132970ec1e1bb50ac2eb026d9f234'
        );
        $message = '测试';
        $this->_send_push($deviceTokens,$message);
    }

    /**
     * 发送push函数
     *
     * @access private
     * @param array $devices
     * @param string 
     * @return void
     */
    private function _send_push($devices,$message)
    {
        if (empty($devices) || empty($message)) {
            return ;
        }

        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', 'ck.pem');
        stream_context_set_option($ctx, 'ssl', 'passphrase', $this->config->item('push_key_passphrase'));
        
        $fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err,
            $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
        
        if (!$fp)
            exit("Failed to connect: $err $errstr" . PHP_EOL);

        // Create the payload body
        $body['aps'] = array(
            'alert' => $message,
            'sound' => 'default'
        );
        $payload = json_encode($body);

        foreach ($devices as $deviceToken) {
            $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
            $r = fwrite($fp, $msg, strlen($msg));
            if ($r) {
                echo $deviceToken . ' successfully delivered .<br>';
            } else {
                echo $deviceToken . ' not delivered <br>';
            }
        }
        fclose($fp);
    }
}
