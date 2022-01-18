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
use Carbon\Carbon;

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
     * @Route("/get/id/{code}", name="get_one_bull_by_code", methods={"GET"})
     */
    public function getIdByCode($code): JsonResponse
    {
        $bull = $this->BullRepository->findOneBy(['code' => $code]);

        $data = [
            'id' => $bull->getId(),
        ];

        return new JsonResponse(['bull' => $data], Response::HTTP_OK);
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
            'week_milk'  => $bull->getWeekMilk(),
            'week_food'  => $bull->getWeekFood(),
            'birthday'   => Carbon::parse($bull->getBirthday())->format('d-m-Y'),
            'age'        => Carbon::parse($bull->getBirthday())->age,
            'sign'       => $this->getSign($bull->getWeight()),
            'status'     => $bull->getStatus(),
            'status_mock' => $this->statusFilter($bull->getWeekMilk(), $bull->getWeekFood(), $bull->getBirthday(), $bull->getWeight(),  $bull->getStatus()),
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
                'birthday'   => Carbon::parse($bull->getBirthday())->format('d-m-Y'),
                'age'        => Carbon::parse($bull->getBirthday())->age,
                'sign'       => $this->getSign($bull->getWeight()),
                'status'     => $bull->getStatus(),
                'status_mock' => $this->statusFilter($bull->getWeekMilk(), $bull->getWeekFood(), $bull->getBirthday(), $bull->getWeight(),  $bull->getStatus()),
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
                'week_milk'  => $bull->getWeekMilk(),
                'week_food'  => $bull->getWeekFood(),
                'birthday'   => Carbon::parse($bull->getBirthday())->format('d-m-Y'),
                'age'        => Carbon::parse($bull->getBirthday())->age,
                'sign'       => $this->getSign($bull->getWeight()),
                'status'     => $bull->getStatus(),
                'status_mock' => $this->statusFilter($bull->getWeekMilk(), $bull->getWeekFood(), $bull->getBirthday(), $bull->getWeight(),  $bull->getStatus()),
            ];
        }

        return new JsonResponse(['bulls' => $data], Response::HTTP_OK);
    }
    /**
     * @Route("/get-report", name="get_report_bulls", methods={"GET"})
     */
    public function getBullsReport(): JsonResponse
    {
        $bulls = $this->BullRepository->findAll();

        $weekMilk = 0;
        $weekFood = 0;
        $weekDead = 0;
        $totalOlder = 0;
        $totalSign = 0;
        $totalMilk = 0;
        $totalMilkFood = 0;

        foreach ($bulls as $bull) {
            $weekMilk += $bull->getWeekMilk();
            $weekFood += $bull->getWeekFood();

            if ($bull->getStatus() == 'dead') {
                $weekDead++;
            }

            if (Carbon::parse($bull->getBirthday())->age > 5) {
                $totalOlder++;
            }

            if ($this->getSign($bull->getWeight()) > 18) {
                $totalSign++;
            }

            if ($bull->getWeekMilk() < 40) {
                $totalMilk++;
            }

            if ($bull->getWeekMilk() < 70 && $bull->getWeekFood() > 50) {
                $totalMilkFood++;
            }
        }

        $data = [
            'readyTo' => [
                'older' => $totalOlder,
                'sign' => $totalSign,
                'milk' => $totalMilk,
                'food_milk' => $totalMilkFood,
            ],
            'week' => [
                'milk' => $weekMilk,
                'food' => $weekFood,
                'dead' => $weekDead,
            ],
        ];

        return new JsonResponse(['report' => $data], Response::HTTP_OK);
    }
    /**
     * @Route("/update/{id}", name="update_bull", methods={"PUT"})
     */
    public function updateBull($id, Request $request): JsonResponse
    {
        $bull = $this->BullRepository->findOneBy(['id' => $id]);
        $data = json_decode($request->getContent(), true);

        $this->BullRepository->updateBull($bull, $data);

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

    // Common Functions
    private function getSign($weight)
    {
        $sign = round($weight * 0.5 / 15, 0);
        return $sign;
    }

    private function statusFilter($weekMilk, $weekFood, $birthday, $weight, $status)
    {
        $bullAge  = Carbon::parse($birthday)->age;
        $bullSing = $this->getSign($weight);

        if ($status === 'dead') {
            return 'dead';
        } elseif ($weekMilk < 40 || ($weekMilk < 70 and $weekFood > 50) || $bullAge > 5 || $bullSing > 18) {
            return 'ready';
        } else {
            return 'working';
        }
    }
}
