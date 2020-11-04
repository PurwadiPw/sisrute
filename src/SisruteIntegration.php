<?php

namespace Sisrute;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;

class SisruteIntegration
{
    public $client;
    public $headers;

    // 1. X-cons-id
    public $cons_id;

    // 2. X-Timestamp
    public $timestamp;

    // 3. X-Signature
    public $signature;
    public $secret_key;

    // 4. Base URL & Service Name
    public $base_url;
    public $service_name;

    public function __construct()
    {
        $this->client = new Client([
            'http_errors' => false,
            'verify' => false,
            'timeout' => 30,
        ]);
    }

    /**
     * [initialize description]
     * @param array $config
     * [
     *      'cons_id' => '12345',
     *      'secret_key' => '1234567890',
     * ]
     */
    public function initialize($config = [])
    {
        foreach ($config as $configName => $configValue) {
            $this->$configName = $configValue;
        }

        $this->setTimestamp()->setSignature()->setHeaders();
        return $this;
    }

    public function setHeaders()
    {
        $this->headers = [
            'X-cons-id' => $this->cons_id,
            'X-Timestamp' => $this->timestamp,
            'X-Signature' => $this->signature,
        ];
        return $this;
    }

    public function setSignature()
    {
        $data = $this->cons_id . '&' . $this->timestamp;
        $signature = hash_hmac('sha256', utf8_encode($data), utf8_encode(md5($this->secret_key)), true);
        $encodedSignature = base64_encode($signature);
        $this->signature = $encodedSignature;
        return $this;
    }

    public function setTimestamp()
    {
        $date = new \DateTime(null, new \DateTimeZone("UTC"));
        $this->timestamp = $date->getTimestamp();
        return $this;
    }

    public function timeoutResponse()
    {
        $output = [
            'status' => '202',
            'success' => false,
            'detail' => 'Koneksi ke server KEMKES bermasalah. Harap hubungi IT RS.',
            'data' => [],
        ];
        return json_encode($output);
    }

    public function get($feature)
    {
        $url = $this->base_url . '/' . $feature;
        $this->headers['Content-Type'] = 'application/json;';
        $this->headers['Accept'] = 'application/json;';
        try {
            $response = $this->client->request('GET', $url, ['headers' => $this->headers])->getBody()->getContents();
        } catch (ClientException $e) {
            $response = $this->timeoutResponse();
        } catch (RequestException $e) {
            $response = $this->timeoutResponse();
        } catch (Exception $e) {
            $response = $e->getResponse()->getBody();
        }
        return $response;
    }

    public function post($feature, $data = [], $header = null)
    {
        $url = $this->base_url . '/' . $feature;
        $this->headers['Content-Type'] = 'application/json';
        $this->headers['Accept'] = 'application/json';
        try {
            $response = $this->client->request('POST', $url, ['headers' => $this->headers, 'json' => $data])->getBody()->getContents();
        } catch (ClientException $e) {
            $response = $this->timeoutResponse();
        } catch (RequestException $e) {
            $response = $this->timeoutResponse();
        } catch (Exception $e) {
            $response = $e->getResponse()->getBody();
        }
        return $response;
    }

    public function put($feature, $data = [])
    {
        $url = $this->base_url . '/' . $feature;
        $this->headers['Content-Type'] = 'application/json';
        $this->headers['Accept'] = 'application/json';
        try {
            $response = $this->client->request('PUT', $url, ['headers' => $this->headers, 'json' => $data])->getBody()->getContents();
        } catch (ClientException $e) {
            $response = $this->timeoutResponse();
        } catch (RequestException $e) {
            $response = $this->timeoutResponse();
        } catch (Exception $e) {
            $response = $e->getResponse()->getBody();
        }
        return $response;
    }

    public function delete($feature, $data = [])
    {
        $this->headers['Content-Type'] = 'Application/x-www-form-urlencoded';
        try {
            $response = $this->client->request('DELETE', $this->base_url . '/' . $feature, ['headers' => $this->headers, 'json' => $data])->getBody()->getContents();
        } catch (ClientException $e) {
            $response = $this->timeoutResponse();
        } catch (RequestException $e) {
            $response = $this->timeoutResponse();
        } catch (Exception $e) {
            $response = $e->getResponse()->getBody();
        }
        return $response;
    }
}
