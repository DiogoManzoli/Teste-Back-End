<?php

namespace App\DTO;

use App\Entity\Resgate;

class ResgatarDTO
{
    public function __construct(public int $investimentoId, public ?Resgate $Resgate = null)
    {   
    
    }

    public function getInvestimentoId(): int{
        return $this->investimentoId;
    }
    public function setInvestimentoId(int $investimentoId){
        $this->investimentoId = $investimentoId;
    }

    public function getResgate():Resgate{
        return $this->Resgate;
    }
    public function setResgate(Resgate $Resgate): void{
        $this->Resgate = $Resgate;
    }

}