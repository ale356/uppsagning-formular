<?php

// src/Controller/CancelServiceController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\CancelServiceType;

class CancelServiceController extends AbstractController
{
    #[Route('/cancel-service', name: 'cancel_service')]
    public function cancelService(Request $request): Response
    {
        // Dummy user and services data
        $userData = [
            'id' => 1,
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
        ];

        $userServices = [
            ['id' => 1, 'name' => 'Service A'],
            ['id' => 2, 'name' => 'Service B'],
            ['id' => 3, 'name' => 'Service C'],
        ];

        // Create and handle the form
        $form = $this->createForm(CancelServiceType::class, null, ['services' => $userServices]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Collect selected services.
            $selectedServices = [];
            foreach ($userServices as $service) {
                $fieldName = "service_{$service['id']}";
                if ($form->get($fieldName)->getData()) {
                    $selectedServices[] = $service['name'];
                }
            }
            if (empty($selectedServices)) {
                $this->addFlash('error', 'You must select at least one service to cancel.');
                return $this->redirectToRoute('cancel_service');
            }

            // Store the data and redirect to confirmation page
            $session = $this->get('session');
            $session->set('selectedServices', $selectedServices);

            return $this->redirectToRoute('cancel_service_summary');
        }

        return $this->render('cancel_service/index.html.twig', [
            'form' => $form->createView(),
            'user' => $userData,
        ]);
    }

    #[Route('/cancel-service/summary', name: 'cancel_service_summary')]
    public function cancelServiceSummary(Request $request): Response
    {
        $session = $this->get('session');
        $selectedServices = $session->get('selectedServices', []);

        if (empty($selectedServices)) {
            return $this->redirectToRoute('cancel_service');
        }

        return $this->render('cancel_service/summary.html.twig', [
            'selectedServices' => $selectedServices,
        ]);
    }

    #[Route('/cancel-service/confirm', name: 'cancel_service_confirm', methods: ['POST'])]
    public function confirmCancelService(Request $request): Response
    {
        $session = $this->get('session');
        $selectedServices = $session->get('selectedServices', []);

        // Simulate API POST request
        $response = $this->mockApiRequest($selectedServices);

        if ($response['status'] === 200) {
            $this->addFlash('success', 'Services canceled successfully.');
        } else {
            $this->addFlash('error', 'An error occurred while canceling the services.');
        }

        return $this->redirectToRoute('homepage');
    }

    private function mockApiRequest(array $services): array
    {
        // Simulating a REST API call and response
        return ['status' => 200, 'message' => 'Services canceled'];
    }
}
