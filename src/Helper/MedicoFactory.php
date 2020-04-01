<?php
namespace App\Helper;

use App\Entity\Medico;



class MedicoFactory 
{
    public function criarMedico(string $json): Medico
    {
        $dadoToJson = json_decode($json);

        $medico = new Medico();
        $medico->crm = $dadoToJson->crm;
        $medico->nome = $dadoToJson->nome;

        return $medico;
    }
}
