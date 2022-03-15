<?php

declare(strict_types=1);

namespace Minicli\Curly;

interface AgentInterface
{
    public function get(string $endpoint, array $headers = [], bool $header_out = false): array;

    public function post(string $endpoint, array $params, array $headers = [], bool $header_out = false): array;

    public function delete(string $endpoint, array $headers = [], bool $header_out = false): array;

    public function getRequestInfo(): array;
}
