<?php

declare(strict_types=1);

namespace Minicli\Curly;

class CurlAgent implements AgentInterface
{
    /** @var array */
    public array $last_request_info = [];

    public function getRequestInfo(): array
    {
        return $this->last_request_info;
    }

    /**
     * Makes a GET query
     * @param string $endpoint API endpoint
     * @param array $headers optional headers
     * @param bool $header_out
     * @return array
     */
    public function get(string $endpoint, array $headers = [], bool $header_out = false): array
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_URL => $endpoint,
            CURLINFO_HEADER_OUT => $header_out,
        ]);

        return $this->getQueryResponse($curl);
    }

    /**
     * Makes a POST query
     * @param string $endpoint
     * @param array $params
     * @param array $headers
     * @param bool $header_out
     * @return array
     */
    public function post(string $endpoint, array $params, array $headers = [], bool $header_out = false): array
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
     * @param string $endpoint
     * @param array $headers
     * @param bool $header_out
     * @return array
     */
    public function delete(string $endpoint, array $headers = [], bool $header_out = false): array
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
     * Checks for CURLOPT_HTTP_VERSION to specify
     * HTTP1.0, HTTP1.1, or HTTP2.0 request.
     * Defaults to HTTP1.1.
     * @return int
     */
    protected function getHttpVersion(): int
    {
	$http_version = CURL_HTTP_VERSION_1_1;
	$curlopt = getenv('CURLOPT_HTTP_VERSION');
	switch ($curlopt) {
	    case false:
                break;
            case 'CURL_HTTP_VERSION_1_0':
                $http_version = constant($curlopt);
                break;
            case 'CURL_HTTP_VERSION_1_1':
                $http_version = constant($curlopt);
                break;
            case 'CURL_HTTP_VERSION_2':
                $http_version = constant($curlopt);
                break;
            case 'CURL_HTTP_VERSION_2_0':
                $http_version = constant($curlopt);
                break;
            default:
		echo "Unknown HTTP version $curlopt, continuing anyway";
		$http_version = 0;
        }
	return $http_version;
    }

    /**
     * Execs curl and saves information about the request.
     * Returns an array with two items: 'code' and 'body'.
     * @param $curl
     * @return array
     */
    protected function getQueryResponse($curl): array
    {
        curl_setopt($curl, CURLOPT_HTTP_VERSION, $this->getHttpVersion());
        $response = curl_exec($curl);
        $this->last_request_info = curl_getinfo($curl);
        $response_code = $this->last_request_info['http_code'];

        curl_close($curl);

        return [ 'code' => $response_code, 'body' => $response ];
    }
}
