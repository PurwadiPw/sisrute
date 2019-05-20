<?php

namespace Sisrute\v1;

use Sisrute\SisruteIntegration;
use GuzzleHttp\Exception\ClientException;

class Referensi extends SisruteIntegration
{
    public function __construct()
    {
        parent::__construct();
    }

    public function faskes($kdFaskes = null)
    {
        $service = $kdFaskes == null ? 'referensi/faskes' : 'referensi/faskes/'.$kdFaskes;
        $response = $this->get($service);
        return json_decode($response, true);
    }

    public function alasanRujukan($kdAlasan = null)
    {
        $service = $kdAlasan == null ? 'referensi/alasanrujukan' : 'referensi/alasanrujukan/'.$kdAlasan;
        $response = $this->get($service);
        return json_decode($response, true);
    }

    public function diagnosa($kdIcd = null)
    {
        $service = $kdIcd == null ? 'referensi/diagnosa' : 'referensi/diagnosa/'.$kdIcd;
        $response = $this->get($service);
        return json_decode($response, true);
    }
}