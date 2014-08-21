<?php

namespace DZunke\SlackBundle\Slack\Client;

class Response
{
    const ERROR_REQUEST_ERROR = 'request_error';

    const ERROR_NOT_AUTHED        = 'not_authed';
    const ERROR_INVALID_AUTH      = 'invalid_auth';
    const ERROR_ACCOUNT_INACTIVE  = 'account_inactive';
    const ERROR_CHANNEL_NOT_FOUND = 'channel_not_found';
    const ERROR_IS_ARCHIVED       = 'is_archived';
    const ERROR_MSG_TO_LONG       = 'msg_too_long';
    const ERROR_NO_TEXT           = 'no_text';
    const ERROR_RATE_LIMITED      = 'rate_limited';

    /**
     * @var bool
     */
    protected $status;

    /**
     * @var string
     */
    protected $error;

    /**
     * @param bool $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = (bool)$status;

        return $this;
    }

    /**
     * @return bool
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $error
     * @return $this
     */
    public function setError($error)
    {
        $this->error = $error;

        return $this;
    }

    /**
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param \Guzzle\Http\Message\Response $guzzleResponse
     * @return Response
     */
    public static function parseGuzzleResponse(\Guzzle\Http\Message\Response $guzzleResponse)
    {
        $response = new self();

        if ($guzzleResponse->getStatusCode() != 200) {
            $response->setStatus('error');
            $response->setError($response::ERROR_REQUEST_ERROR);

            return $response;
        }

        $responseArray = json_decode($guzzleResponse->getBody(true), true);

        $response->setStatus($responseArray['ok']);

        if ($response->getStatus() === false) {
            $response->setError($responseArray['error']);

            return $response;
        }

        return $response;
    }
}