<?php

class user_verifier
{
    public function __construct($privateKey, $captchaToken, $minRecaptchaScore)
    {
        $remoteIp = qa_remote_ip_address();
        $this->minRecaptchaScore = $minRecaptchaScore;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, "secret=${privateKey}&response=${captchaToken}&remoteip=${remoteIp}");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $rawResponse = curl_exec($curl);
        $this->curlError = curl_errno($curl);
        curl_close($curl);

        $this->response = json_decode($rawResponse, true);
    }

    public function is_human()
    {
        return
            $this->curlError === 0 &&
            $this->response['success'] &&
            $this->response['score'] >= $this->minRecaptchaScore
        ;
    }

    private $minRecaptchaScore;
    private $curlError;
    private $response;
}
