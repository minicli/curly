<?php

declare(strict_types=1);

namespace Minicli\Curly;

class Client
{
    public ?AgentInterface $agent;

    public function __construct(AgentInterface $agent = null)
    {
        if ($agent ===  null) {
            $agent = new CurlAgent();
        }

        $this->agent = $agent;
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
        return $this->agent->get($endpoint, $this->getHeaders($headers), $header_out);
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
        return $this->agent->post($endpoint, $params, $this->getHeaders($headers), $header_out);
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
        return $this->agent->delete($endpoint, $this->getHeaders($headers), $header_out);
    }

    /**
     * @param array $headers
     * @return array
     */
    public function getHeaders(array $headers = []): array
    {
        return array_merge([
            'User-Agent: Curly 0.2',
        ], $headers);
    }

    /**
     * @return array
     */
    public function getRequestInfo(): array
    {
        return $this->agent->getRequestInfo();
    }
}
