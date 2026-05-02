<?php


namespace App\Controller;


use App\DataTables\JanreDataTable;
use App\Entity\Janre;
use App\Form\JanreType;
use App\Service\JanreService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class JanreController extends AbstractController
{
    public function __construct(
       private JanreService $janreService
    )
    {}

    #[Route('/janre', name: 'app_janre')]
    public function index(JanreDataTable $table, Request $request): Response
    {
        $err = $request->query->get('err');
        $table->handleRequest($request);

        if ($table->isRequestHandled()) {
            return $table->getResponse();
        }

        return $this->render('janre/index.html.twig', [
            'table' => $table->getDataTable(),
            'errMessage' => $err
        ]);
    }

    #[Route('/add_janre', name: 'app_add_janre', methods: ['GET', 'POST'])]
    public function addJanre(Request $request): Response
    {
        $janre = new Janre();
        return $this->renderAddOrEditForm($janre, $request, 'janre/add_or_edit/add.html.twig');
    }

    #[Route('/edit_janre/{id}', name: 'app_edit_janre', methods: ['GET', 'POST'])]
    public function editJanre(Janre $janre, Request $request): Response
    {
        return $this->renderAddOrEditForm($janre, $request, 'janre/add_or_edit/edit.html.twig');
    }

    #[Route('/remove_janre/{id}', name: 'app_edit_janre', methods: ['POST'])]
    public function removeJanre(Janre $janre):Response
    {
        $errMess = $this->janreService->removeJanre($janre);

        if($errMess){
            return  $this->redirectToRoute('app_janre', ['err' => $errMess], Response::HTTP_SEE_OTHER);
        }

        return  $this->redirectToRoute('app_janre', [], Response::HTTP_SEE_OTHER);

    }

    private function renderAddOrEditForm(Janre $janre, Request $request, string $viewName):Response
    {
        $form = $this->createForm(JanreType::class, $janre);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->janreService->addOrEditJanre($janre);
            return $this->redirectToRoute('app_janre', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render($viewName, [
            'janre' => $janre,
            'entityForm' => $form
        ]);
    }
}
