<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RecaptchaVerifier
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private string $siteKey,
        private string $secretKey
    ) {
    }

    public function isConfigured(): bool
    {
        return trim($this->siteKey) !== '' && trim($this->secretKey) !== '';
    }

    public function getSiteKey(): string
    {
        return $this->siteKey;
    }

    public function verify(?string $token, ?string $ipAddress): bool
    {
        if (!$this->isConfigured()) {
            return true;
        }

        if (!is_string($token) || trim($token) === '') {
            return false;
        }

        try {
            $payload = [
                'secret' => $this->secretKey,
                'response' => $token,
            ];

            if ($ipAddress) {
                $payload['remoteip'] = $ipAddress;
            }

            $response = $this->httpClient->request('POST', 'https://www.google.com/recaptcha/api/siteverify', [
                'body' => $payload,
            ]);

            $data = $response->toArray(false);

            return (bool) ($data['success'] ?? false);
        } catch (ExceptionInterface) {
            return false;
        }
    }
}
