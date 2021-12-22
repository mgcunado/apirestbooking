<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Accommodation;
use App\Repository\UserRepository;
use App\Service\ReadCsv;
use App\Repository\AccommodationRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class UserController
 * @package App\Controller
 */
class UserController
{
    private $userRepository;
    private $accommodationRepository;

    public function __construct(UserRepository $userRepository, AccommodationRepository $accommodationRepository)
    {
        $this->userRepository = $userRepository;
        $this->accommodationRepository = $accommodationRepository;
    }

    /**
     * @Route("user/{user_id}/accommodations", name="get_accommodations", methods={"GET"})
     */
    public function getAccommodations($user_id, ReadCsv $readCsv): JsonResponse
    {
        $file = 'data.csv';
        $data = $readCsv->getDataFromcsv($user_id, $file);

        if (!$data) {
            return new JsonResponse(['respuesta' => 'The request is not valid!'], Response::HTTP_BAD_REQUEST);
        } else {
            return new JsonResponse($data, Response::HTTP_OK);
        }
    }

    /**
     * @Route("user/{user_id}/accommodations", name="add_accommodations", methods={"POST"})
     */
    public function addAccommodations($user_id, Request $request, ValidatorInterface $validator): JsonResponse
    {

        $data = json_decode($request->getContent(), true);

        $name = $data['trade_name'];
        $type = $data['type'];
        $living_rooms = $data['distribution']['living_rooms'];
        $bedrooms = $data['distribution']['bedrooms'];
        $beds = $data['distribution']['beds'];
        $max_guests = $data['max_guests'];

        $userbuscado = $this->userRepository->findOneBy(['id' => $user_id]);

        $newAccommodation = new Accommodation();

        $newAccommodation
            ->setName($name)
            ->setType($type)
            ->setUser($userbuscado)
            ->setLivingRooms($living_rooms)
            ->setBedrooms($bedrooms)
            ->setBeds($beds)
            ->setMaxGuests($max_guests);

        $errors = $validator->validate($newAccommodation);

        if (count($errors) > 0) {
            return new JsonResponse(['respuesta' => (string) $errors], Response::HTTP_BAD_REQUEST);
        } else {
            $this->userRepository->addAccommodation($userbuscado, $newAccommodation);
            return new JsonResponse(['respuesta' => 'Accommodation has been loaded correctly!'], Response::HTTP_CREATED);
        }
    }

    /**
     * @Route("user/{user_id}/accommodations/{id}", name="update_accommodations", methods={"PUT"})
     */
    public function updateAccommodations($user_id, $id, Request $request, ValidatorInterface $validator): JsonResponse
    {
        $accommodation = $this->accommodationRepository->findOneBy(['id' => $id, 'user' => $user_id]);

        if (!$accommodation) {
            return new JsonResponse(['respuesta' => 'Archivo no encontrado!'], Response::HTTP_NOT_FOUND);
        } else {
            $data = json_decode($request->getContent(), true);

            $empty = 0;

            empty($data['trade_name']) ? $empty = 1 : $accommodation->setName($data['trade_name']);
            empty($data['type']) ? $empty = 1 : $accommodation->setType($data['type']);
            empty($data['distribution']['living_rooms']) ? $empty = 1 : $accommodation->setLivingRooms($data['distribution']['living_rooms']);
            empty($data['distribution']['bedrooms']) ? $empty = 1 : $accommodation->setBedrooms($data['distribution']['bedrooms']);
            empty($data['distribution']['beds']) ? $empty = 1 : $accommodation->setBeds($data['distribution']['beds']);
            empty($data['max_guests']) ? $empty = 1 : $accommodation->setMaxGuests($data['max_guests']);
            $accommodation->setLastUpdate(new \DateTime());

            if ($empty != 0) {
                return new JsonResponse(['respuesta' => 'The request is not valid!'], Response::HTTP_BAD_REQUEST);
            } else {

                $errors = $validator->validate($accommodation);

                if (count($errors) > 0) {
                    return new JsonResponse(['respuesta' => (string) $errors], Response::HTTP_BAD_REQUEST);
                } else {
                    $this->accommodationRepository->updateAccommodation($accommodation);
                    return new JsonResponse(['respuesta' => 'Accommodations has been edited succesfully!'], Response::HTTP_OK);
                }
            }
        }
    }

    /**
     * @Route("user", name="add_user", methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $name = $data['name'];

        if (!isset($data['name'])) {
            return new JsonResponse(['respuesta' => 'The request is not valid!'], Response::HTTP_BAD_REQUEST);
        } else {
            $this->userRepository->saveuser($name);
            return new JsonResponse(['respuesta' => 'The user has been successfully registered.'], Response::HTTP_CREATED);
        }
    }

    /**
     * @Route("user/{user_id}/accommodationsdb", name="get_accommodationsdb", methods={"GET"})
     */
    public function getAccommodationsdb($user_id): JsonResponse
    {
        $accommodations = $this->userRepository->getAccommodations($user_id);
        $data = [];
        $weekend = [0, 6];

        foreach ($accommodations as $accommodation) {
            $dayofweek = date('w', strtotime($accommodation->getLastUpdate()->format('Y-m-d')));
            if (in_array($dayofweek, $weekend)) {
                $data[] = [
                    'id' => $accommodation->getId(),
                    'trade_name' => $accommodation->getName(),
                    'type' => $accommodation->getType(),
                    'distribution' => [
                        'living_rooms' => $accommodation->getLivingRooms(),
                        'bedrooms' => $accommodation->getBedrooms(),
                        'beds' => $accommodation->getBeds()
                    ],
                    'max_guests' => $accommodation->getMaxGuests(),
                    'updated_at' => $accommodation->getLastUpdate()->format('Y-m-d'),
                ];
            }
        }

        if (!$data) {
            return new JsonResponse(['respuesta' => 'The request is not valid!'], Response::HTTP_BAD_REQUEST);
        } else {
            return new JsonResponse($data, Response::HTTP_OK);
        }
    }
}
