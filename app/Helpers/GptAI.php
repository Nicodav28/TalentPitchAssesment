<?php

namespace App\Helpers;

use GuzzleHttp\Client;

class GptAI
{
    private $httpClient;

    public function __construct()
    {
        $this->httpClient = new Client([
            'base_uri' => 'https://api.openai.com/v1/chat/completions',
            'timeout'  => 30,
            'verify' => base_path('storage/certificates/cacert.pem'),
        ]);
    }

    public function openAI(string $prompt)
    {
        try {
            $response = $this->sendRequest(getenv("OPEN_API_KEY"), $prompt);

            return [
                "error" => false,
                "response" => json_decode($response->getBody()->getContents()),
            ];
        } catch (\Throwable $e) {
            return [
                "error" => true,
                "message" => $e->getMessage(),
            ];
        }
    }

    private function sendRequest(string $apiKey, string $prompt)
    {
        return $this->httpClient->post('completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'model' => 'gpt-4',
                'messages' => [
                    ["role" => "user", "content" => $prompt]
                ],
                'max_tokens' => (int) getenv('MAX_TOKENS_QUANTITY'),
                'temperature' => 0.3
            ]
        ]);
    }
}
