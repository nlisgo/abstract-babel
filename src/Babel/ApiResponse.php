<?php

namespace AbstractBabel\Babel;

use Symfony\Component\HttpFoundation\JsonResponse;

final class ApiResponse extends JsonResponse
{
    public function __construct(array $data = [], int $status = self::HTTP_OK, array $headers = [])
    {
        parent::__construct($data, $status, $headers);

        $this->headers->set('Cache-Control', 'public, max-age=300, stale-while-revalidate=300, stale-if-error=86400');
        $this->headers->set('Vary', 'Accept', false);
    }
}
