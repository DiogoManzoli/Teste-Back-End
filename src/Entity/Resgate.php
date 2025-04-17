<?php

namespace App\Entity;

use App\Repository\ResgateRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ResgateRepository::class)]
class Resgate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $saldoFinalComImposto = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $data_investimento = null;

    #[ORM\ManyToOne(inversedBy: 'investimentos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ProdutoInvestimento $produto_investimento = null;

    #[ORM\ManyToOne(inversedBy: 'investimentos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $id_user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getsaldoFinalComImposto(): ?string
    {
        return $this->saldoFinalComImposto;
    }

    public function setSaldoFinalComImposto(string $saldoFinalComImposto): static
    {
        $this->saldoFinalComImposto = $saldoFinalComImposto;

        return $this;
    }

    public function getDataInvestimento(): ?\DateTimeInterface
    {
        return $this->data_investimento;
    }

    public function setDataInvestimento(\DateTimeInterface $data_investimento): static
    {
        $this->data_investimento = $data_investimento;

        return $this;
    }

    public function getProdutoInvestimento(): ?ProdutoInvestimento
    {
        return $this->produto_investimento;
    }

    public function setProdutoInvestimento(?ProdutoInvestimento $produto_investimento): static
    {
        $this->produto_investimento = $produto_investimento;

        return $this;
    }

    public function getIdUser(): ?User
    {
        return $this->id_user;
    }

    public function setIdUser(?User $id_user): static
    {
        $this->id_user = $id_user;

        return $this;
    }
}
