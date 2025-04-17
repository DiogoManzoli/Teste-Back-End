<?php

namespace App\Entity;

use App\Repository\ProdutoInvestimentoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProdutoInvestimentoRepository::class)]
class ProdutoInvestimento
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nome_investimento = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $data_criacao = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomeInvestimento(): ?string
    {
        return $this->nome_investimento;
    }

    public function setNomeInvestimento(string $nome_investimento): static
    {
        $this->nome_investimento = $nome_investimento;

        return $this;
    }


    public function getDataCriacao(): ?\DateTimeInterface
    {
        return $this->data_criacao;
    }

    public function setDataCriacao(\DateTimeInterface $data_criacao): static
    {
        $this->data_criacao = $data_criacao;

        return $this;
    }
}
