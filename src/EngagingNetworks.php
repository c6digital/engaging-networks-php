<?php

namespace C6Digital\EngagingNetworks;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use C6Digital\EngagingNetworks\Exceptions\AuthenticationException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Traits\Conditionable;

class EngagingNetworks
{
    use Conditionable;

    protected string $baseUrl = 'https://ca.engagingnetworks.app/ens/service';

    protected ?string $token = null;

    protected ?string $proxy = null;

    public function __construct(
        protected string $key,
    ) {}

    public function setBaseUrl(string $url): static
    {
        $this->baseUrl = $url;

        return $this;
    }

    public function proxy(string $proxy): static
    {
        $this->proxy = $proxy;

        return $this;
    }

    public function pageRequest(int|string $pageId, array $data = []): Response
    {
        return $this->post("/page/{$pageId}/process", $data);
    }

    public function getSupporterById(int $supporterId, array $parameters = []): Response
    {
        return $this->get("/supporter/{$supporterId}", $parameters);
    }

    public function authenticate(): void
    {
        $response = Http::withBody($this->key, 'application/json')
            ->when($this->proxy, function (PendingRequest $request) {
                return $request->withOptions([
                    'proxy' => $this->proxy,
                ]);
            })
            ->post('https://ca.engagingnetworks.app/ens/service/authenticate')
            ->json();

        if (! isset($response['ens-auth-token']) && isset($response['message'])) {
            throw new AuthenticationException('Failed to authenticate. [Message] ' . $response['message']);
        }

        $this->token = $response['ens-auth-token'];
    }

    protected function get(string $endpoint, array $data = []): Response
    {
        return $this->baseClient()->get($endpoint, $data);
    }

    protected function post(string $endpoint, array $data = []): Response
    {
        return $this->baseClient()->post($endpoint, $data);
    }

    protected function baseClient(): PendingRequest
    {
        $this->authenticate();

        return Http::asJson()
            ->baseUrl($this->baseUrl)
            ->acceptJson()
            ->withHeaders([
                'ens-auth-token' => $this->token,
            ])
            ->when($this->proxy, function (PendingRequest $request) {
                return $request->withOptions([
                    'proxy' => $this->proxy,
                ]);
            });
    }
}