<?php
class Torrent
{
    private $HOST = "127.0.0.1";
    private $PORT = "9091";
    private $USER = "transmission";
    private $PASSWORD = "transmission";

    public function addTorent($magnet)
    {
        $json = array(
            "arguments" => array(
                'filename' => $magnet
            ),
            "method" => "torrent-add"
        );

        $a = json_encode($json);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_URL, "http://$this->HOST:$this->PORT/transmission/rpc");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $a);
        curl_setopt($ch, CURLOPT_HTTPAUTH, 1);
        curl_setopt($ch, CURLOPT_USERPWD, "$this->USER:$this->PASSWORD");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $r = curl_exec($ch);

        //$ret = preg_match  ( "%.*<code>(X-Transmission-Session-Id: .*?)(</code>.*)%", $r, $result) ;
        $ret = preg_match("%.*\r\n(X-Transmission-Session-Id: .*?)(\r\n.*)%", $r, $result);
        $X_Transmission_Session_Id  = $result[1];
        // echo $result[1];
        // exit();


        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array($X_Transmission_Session_Id));
        $r = curl_exec($ch);

        curl_close($ch);
        $r = json_decode($r);
        return $r->result;
    }
}
