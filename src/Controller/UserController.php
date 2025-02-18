<?php

namespace App\Controller;

use App\Service\UserAnonymizer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/api/user/delete', name: 'api_user_delete', methods: ['POST'])]
    public function deleteUser(
        Request $request,
        EntityManagerInterface $em,
        UserAnonymizer $anonymizer,
    ): JsonResponse {
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        // Anonymisation de l'utilisateur
        $anonymizer->anonymize($user);
        $em->flush();

        return $this->json(['message' => 'User data has been anonymized.']);
    }
}
