<?php

namespace App\Controller;

use App\Repository\BullRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class BullSiteController
 * @package App\Controller
 *
 * @Route(path="/bull")
 */
class BullController extends AbstractController
{
    private $BullRepository;

    public function __construct(BullRepository $BullRepository)
    {
        $this->BullRepository = $BullRepository;
    }

    /**
     * @Route("/add", name="add_bull", methods={"POST"})
     */
    public function addBull(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $code = $data['code'];
        $weight = $data['weight'];
        $birthday = $data['birthday'];
        $weekMilk = $data['weekMilk'];
        $weekFood = $data['weekFood'];

        if (empty($code) || empty($weight) || empty($birthday) || empty($weekMilk) || empty($weekFood)) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        $this->BullRepository->saveBull($code, $weight, $birthday, $weekMilk, $weekFood);

        return new JsonResponse(['status' => 'bull added!'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/get/{id}", name="get_one_bull", methods={"GET"})
     */
    public function getOneBull($id): JsonResponse
    {
        $bull = $this->BullRepository->findOneBy(['id' => $id]);

        $data = [
            'id'         => $bull->getId(),
            'code'       => $bull->getCode(),
            'weight'     => $bull->getWeight(),
            'birthday'   => $bull->getBirthday(),
            'week_milk'  => $bull->getWeekMilk(),
            'week_food'  => $bull->getWeekFood(),
            'sign'       => round($bull->getWeight() * 0.5 / 15, 0),
        ];

        return new JsonResponse(['bull' => $data], Response::HTTP_OK);
    }
    /**
     * @Route("/list", name="list_bulls_per_page", methods={"GET"})
     */
    public function listBulls(PaginatorInterface $paginator, Request $request): JsonResponse
    {
        $page = $request->query->getInt('page', 1);
        $getBulls = $this->BullRepository->findBy(array(), array('id' => 'DESC'));
        $itemPerPage = 10;
        $data = [];

        $bulls = $paginator->paginate(
            $getBulls,
            $page,
            $itemPerPage
        );

        foreach ($bulls as $bull) {
            $data[] = [
                'id'         => $bull->getId(),
                'code'       => $bull->getCode(),
                'weight'     => $bull->getWeight(),
                'week_milk'  => $bull->getWeekMilk(),
                'week_food'  => $bull->getWeekFood(),
                'sign'       => round($bull->getWeight() * 0.5 / 15, 0),
                'birthday'   => $bull->getBirthday(),
            ];
        }

        $pagination = [
            "page" => $bulls->getCurrentPageNumber(),
            "total" => $bulls->getTotalItemCount(),
            "totalPage" => $bulls->getTotalItemCount() / $itemPerPage,
            "resultsPerPage" => $bulls->getItemNumberPerPage(),
        ];

        return new JsonResponse(['bulls' => $data, 'pagination' => $pagination], Response::HTTP_OK);
    }
    /**
     * @Route("/get-all", name="get_all_bulls", methods={"GET"})
     */
    public function getAllBulls(): JsonResponse
    {
        $bulls = $this->BullRepository->findAll();
        $data = [];

        foreach ($bulls as $bull) {
            $data[] = [
                'id'         => $bull->getId(),
                'code'       => $bull->getCode(),
                'weight'     => $bull->getWeight(),
                'birthday'   => $bull->getBirthday(),
                'week_milk'  => $bull->getWeekMilk(),
                'week_food'  => $bull->getWeekFood(),
                'sign'       => round($bull->getWeight() * 0.5 / 15, 0),
            ];
        }

        return new JsonResponse(['bulls' => $data], Response::HTTP_OK);
    }

    /**
     * @Route("/update/{id}", name="update_bull", methods={"PUT"})
     */
    public function updateBull($id, Request $request): JsonResponse
    {
        $bull = $this->BullRepository->findOneBy(['id' => $id]);
        $data = json_decode($request->getContent(), true);

        $this->BullRepository->updateUpdate($bull, $data);

        return new JsonResponse(['status' => 'bull updated!']);
    }

    /**
     * @Route("/delete/{id}", name="delete_bull", methods={"DELETE"})
     */
    public function deleteBull($id): JsonResponse
    {
        $bull = $this->BullRepository->findOneBy(['id' => $id]);

        $this->BullRepository->removeBull($bull);

        return new JsonResponse(['status' => 'bull deleted']);
    }
}
