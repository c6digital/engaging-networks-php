<?php

namespace C6Digital\EngagingNetworks;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use C6Digital\EngagingNetworks\Exceptions\AuthenticationException;

class EngagingNetworks
{
    protected string $baseUrl = 'https://ca.engagingnetworks.app/ens/service';

    protected ?string $token = null;

    public function __construct(
        protected string $key,
    ) {}

    public function setBaseUrl(string $url): static
    {
        $this->baseUrl = $url;

        return $this;
    }

    public function pageRequest(int|string $pageId, array $data = []): Response
    {
        return $this->post("/page/{$pageId}/process", $data);
    }

    public function authenticate(): void
    {
        $response = Http::withBody($this->key, 'application/json')
            ->post('https://ca.engagingnetworks.app/ens/service/authenticate')
            ->json();

        if (! $response['ens-auth-token'] && isset($response['message'])) {
            throw new AuthenticationException('Failed to authenticate. [Message] ' . $response['message']);
        }

        $this->token = $response['ens-auth-token'];
    }

    protected function post(string $endpoint, array $data = []): Response
    {
        $this->authenticate();

        return Http::asJson()
            ->acceptJson()
            ->withHeaders([
                'ens-auth-token' => $this->token,
            ])
            ->post(rtrim($this->baseUrl, '/') . '/' . $endpoint, $data);
    }
}