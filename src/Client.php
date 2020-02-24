<?php

namespace Minicli\Curly;

class Client
{
    /** @var array */
    protected $last_request_info;

    /**
     * Makes a GET query
     * @param string $endpoint API endpoint
     * @param array $headers optional headers
     * @param bool $header_out
     * @return array
     */
    public function get($endpoint, array $headers = [], $header_out = false)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_URL => $endpoint,
            CURLINFO_HEADER_OUT => $header_out
        ]);

        return $this->getQueryResponse($curl);
    }

    /**
     * Makes a POST query
     * @param $endpoint
     * @param array $params
     * @param array $headers
     * @param bool $header_out
     * @return array
     */
    public function post($endpoint, array $params, $headers = [], $header_out = false)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($params),
            CURLOPT_URL => $endpoint,
            CURLINFO_HEADER_OUT => $header_out,
            CURLOPT_TIMEOUT => 120,
        ]);

        return $this->getQueryResponse($curl);
    }

    /**
     * Makes a DELETE query
     * @param $endpoint
     * @param array $headers
     * @param bool $header_out
     * @return array
     */
    public function delete($endpoint, $headers = [], $header_out = false)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "DELETE",
            CURLOPT_URL => $endpoint,
            CURLINFO_HEADER_OUT => $header_out,
        ]);

        return $this->getQueryResponse($curl);
    }

    /**
     * Execs curl and saves information about the request.
     * Returns an array with two items: 'code' and 'body'.
     * @param $curl
     * @return array
     */
    protected function getQueryResponse($curl)
    {
        $response = curl_exec($curl);
        $this->last_request_info = curl_getinfo($curl);
        $response_code = $this->last_request_info['http_code'];

        curl_close($curl);

        return [ 'code' => $response_code, 'body' => $response ];
    }
}