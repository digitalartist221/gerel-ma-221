<?php
namespace Packages\Paytech;

use Core\Config;

class Paytech {
    private string $apiKey;
    private string $apiSecret;
    private string $apiUrl = "https://paytech.sn/api/payment/request-payment";

    public function __construct() {
        $config = Config::get('paytech') ?? [];
        $this->apiKey = $config['api_key'] ?? '';
        $this->apiSecret = $config['api_secret'] ?? '';
    }

    /**
     * Crée une demande de paiement vers Paytech SN
     */
    public function send($item_name, $item_price, $item_id, $ref_command, $currency = 'XOF') {
        $data = [
            'item_name' => $item_name,
            'item_price' => $item_price,
            'item_id' => $item_id,
            'ref_command' => $ref_command,
            'command_name' => "Paiement Madeline Document #$ref_command",
            'currency' => $currency,
            'env' => Config::get('env') === 'local' ? 'test' : 'live',
            'success_url' => Config::get('app.url') . "/payment/success?ref=$ref_command",
            'cancel_url' => Config::get('app.url') . "/payment/cancel?ref=$ref_command",
        ];

        $headers = [
            "Accept: application/json",
            "Content-Type: application/json",
            "API_KEY: " . $this->apiKey,
            "API_SECRET: " . $this->apiSecret
        ];

        return $this->request($data, $headers);
    }

    private function request($data, $headers) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->apiUrl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        
        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) return ['success' => 0, 'error' => $error];
        
        return json_decode($response, true);
    }
}
