<?php

namespace App\Controller;

use App\Entity\Investimento;
use App\Entity\ProdutoInvestimento;
use App\Form\InvestimentoFormType;
use App\Repository\ProdutoInvestimentoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
final class ProdutosController extends AbstractController
{
    private $em;
    private $produtoInvestimentoRepository;
    public function __construct(EntityManagerInterface $entityManager, ProdutoInvestimentoRepository $ProdutoInvestimentoRepository)
    {
        $this->em = $entityManager;
        $this-> produtoInvestimentoRepository = $ProdutoInvestimentoRepository;
    } 

    #[Route('/investimento', name: 'app_investimento')]
public function formulario(): Response
{
    $this->denyAccessUnlessGranted('ROLE_USER');

    $user = $this->getUser();
    $produtos = $this->produtoInvestimentoRepository->findAll();

    $forms = [];

    foreach ($produtos as $produto) {
        $investimento = new Investimento();
        $investimento->setIdUser($user);

        $form = $this->createForm(InvestimentoFormType::class, $investimento, [
            'action' => $this->generateUrl('app_investimento_submit', ['id' => $produto->getId()]),
        ]);
        $forms[] = [
            'produto' => $produto,
            'form' => $form->createView()
        ];
    }

    return $this->render('investimento/index.html.twig', [
        'produtosForm' => $forms
    ]);
}

    #[Route('/investimento/{id}', name: 'app_investimento_submit')]
    public function index(Request $request, ProdutoInvestimento $produto): Response
    {
       $this->denyAccessUnlessGranted('ROLE_USER');
       
       $user = $this->getUser();
       $investimento = new Investimento();
       $investimento->setIdUser($user);
       $investimento->setProdutoInvestimento($produto); 
       
        $form = $this->createForm(InvestimentoFormType::class, $investimento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($investimento);
            $this->em->flush();

            $this->addFlash('success', 'Investimento criado com sucesso!');
        }
        return $this->redirectToRoute('app_investimento');
    }
}