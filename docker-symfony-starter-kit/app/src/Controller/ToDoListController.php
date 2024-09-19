<?php
/**
 * ToDoList controller.
 */

namespace App\Controller;

use App\Entity\ToDoList;
use App\Form\Type\ToDoListType;
use App\Service\ToDoListServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ToDoListController.
 */
#[Route('/todolist')]
class ToDoListController extends AbstractController
{
    /**
     * @param ToDoListServiceInterface $todolistService
     * @param TranslatorInterface      $translator
     */
    public function __construct(private readonly ToDoListServiceInterface $todolistService, private readonly TranslatorInterface $translator)
    {
    }

    /**
     * Index action.
     *
     * @param int $page Page number
     *
     * @return Response HTTP response
     */
    #[Route(name: 'todolist_index', methods: 'GET')]
    public function index(#[MapQueryParameter] int $page = 1): Response
    {
        $pagination = $this->todolistService->getPaginatedList($page);

        return $this->render('todolist/index.html.twig', ['pagination' => $pagination]);
    }

    /**
     * Show action.
     *
     * @param ToDoList $todolist ToDoList
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}',
        name: 'todolist_show',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET'
    )]
    public function show(ToDoList $todolist): Response
    {
        return $this->render('todolist/show.html.twig', ['todolist' => $todolist]);
    }

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route('/create', name: 'todolist_create', methods: 'GET|POST')]
    public function create(Request $request): Response
    {
        $todolist = new ToDoList();
        $form = $this->createForm(ToDoListType::class, $todolist, ['action' => $this->generateUrl('todolist_create')]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->todolistService->save($todolist);

            $this->addFlash(
                'success',
                $this->translator->trans('Task created successfully')
            );

            return $this->redirectToRoute('todolist_index');
        }

        return $this->render('todolist/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * Edit action.
     *
     * @param Request  $request  HTTP request
     * @param ToDoList $todolist ToDoList entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/edit', name: 'todolist_edit', requirements: ['id' => '[1-9]\d*'], methods: 'GET|PUT')]
    public function edit(Request $request, ToDoList $todolist): Response
    {
        $form = $this->createForm(
            ToDoListType::class,
            $todolist,
            [
                'method' => 'PUT',
                'action' => $this->generateUrl('todolist_edit', ['id' => $todolist->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->todolistService->save($todolist);

            $this->addFlash(
                'success',
                $this->translator->trans('Task edited successfully')
            );

            return $this->redirectToRoute('todolist_index');
        }

        return $this->render(
            'todolist/edit.html.twig',
            [
                'form' => $form->createView(),
                'todolist' => $todolist,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param Request  $request  HTTP request
     * @param ToDoList $todolist ToDoList entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/delete', name: 'todolist_delete', requirements: ['id' => '[1-9]\d*'], methods: 'GET|DELETE')]
    public function delete(Request $request, ToDoList $todolist): Response
    {
        $form = $this->createForm(
            FormType::class,
            $todolist,
            [
                'method' => 'DELETE',
                'action' => $this->generateUrl('todolist_delete', ['id' => $todolist->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->todolistService->delete($todolist);

            $this->addFlash(
                'success',
                $this->translator->trans('Task deleted successfully')
            );

            return $this->redirectToRoute('todolist_index');
        }

        return $this->render(
            'todolist/delete.html.twig',
            [
                'form' => $form->createView(),
                'todolist' => $todolist,
            ]
        );
    }
}
