<?php

namespace Sisrute\v1;

use Sisrute\SisruteIntegration;
use GuzzleHttp\Exception\ClientException;

class Rujukan extends SisruteIntegration
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function insertRujukan($data = [])
    {
        $response = $this->post('rujukan', $data);
        return json_decode($response, true);
    }

    public function getRujukan($noRujukan = null, $tgl = null, $respon = true)
    {
        $service = 'rujukan';
        $service = $noRujukan != null ? $service .= '?nomor='.$noRujukan : $service;
        $service = (
            ($tgl != null && $noRujukan != null) ? $service .= '&tanggal='.$tgl : 
            (($tgl !=null && $noRujukan == null) ? '?tanggal='.$tgl : $service)
        );
        $response = $this->get($service);
        return json_decode($response, true);
    }

    public function updateRujukan($noRujukan, $data)
    {
        $response = $this->put('rujukan/'.$noRujukan, $data);
        return json_decode($response, true);
    }

    public function sendNotif($noRujukan, $data = [])
    {
        $response = $this->post('rujukan/notifrujukan/'.$noRujukan, $data);
        return json_decode($response, true);
    }

    public function jawabRujukan($noRujukan, $data = [])
    {
        $response = $this->put('rujukan/jawab/'.$noRujukan, $data);
        return json_decode($response, true);
    }

    public function batalRujukan($noRujukan, $data = [])
    {
        $response = $this->put('rujukan/batal/'.$noRujukan, $data);
        return json_decode($response, true);
    }
}