<?php

namespace App\Controller;

use App\Form\DateTimezoneFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DateController extends AbstractController
{
    /**
     * @Route("/date", name="app_date")
     */
    public function new(Request $request): Response
    {

        $form = $this->createForm(DateTimezoneFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
        }

        return $this->render('date/index.html.twig', [
            'controller_name' => 'DateController',
            'form'            => $form->createView(),
        ]);
    }
}
