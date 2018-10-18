<?php

class Email {

    public $to;
    public $subject;
    public $body;
    private $replyTo;

    /**
     * Email constructor.
     * @param $to
     * @param $subject
     * @param $body
     */
    public function __construct($to, $subject, $body) {
        $this->to = $to;
        $this->subject = $subject;
        $this->body = $body;
        $this->replyTo = Keys::ReplyTo;
    }

    public function send() {
        $key = Keys::Mailgun;
        $url = "https://api:key-$key@api.mailgun.net/v3/mx.iwgb.org.uk/messages";
        $result = self::requestJSON($url, array(
            'from'      => "noreply@iwgb.org.uk",
            'to'        => $this->to,
            'subject'   => $this->subject,
            'text'      => $this->body,
            'h:Reply-To'=> $this->replyTo,
        ));
    }

    private static function requestJSON($url, $data) {
        $stream = stream_context_create(array(
            'http' => array(
                'header'    => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'    => "POST",
                'content'   => http_build_query($data),
            ),
        ));
        try {
            return json_decode(file_get_contents($url, false, $stream), true);
        } catch (Exception $e) {
            return false;
        }
    }

}