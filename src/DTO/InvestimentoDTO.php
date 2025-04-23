<?php

namespace App\DTO;

class InvestimentoDTO
{
    private $user;
    private $produtoInvestimento;
    private $saldo_inicial;
    private $data_investimento;

    public function __construct(
        $user,
        $produtoInvestimento = null,
        $saldo_inicial = null,
        $data_investimento = null
    ) {
        $this->user = $user;
        $this->produtoInvestimento = $produtoInvestimento;
        $this->saldo_inicial = $saldo_inicial;
        $this->data_investimento = $data_investimento;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user): void
    {
        $this->user = $user;
    }

    public function getProdutoInvestimento()
    {
        return $this->produtoInvestimento;
    }

    public function setProdutoInvestimento($produtoInvestimento): void
    {
        $this->produtoInvestimento = $produtoInvestimento;
    }

    public function getSaldoInicial()
    {
        return $this->saldo_inicial;
    }

    public function setSaldoInicial($saldo_inicial): void
    {
        $this->saldo_inicial = $saldo_inicial;
    }

    public function getDataInvestimento()
    {
        return $this->data_investimento;
    }

    public function setDataInvestimento($data_investimento): void
    {
        $this->data_investimento = $data_investimento;
    }
}
