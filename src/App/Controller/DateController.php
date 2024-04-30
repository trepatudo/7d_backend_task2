<?php

namespace App\Controller;

use App\Form\DateTimezoneFormType;
use Domain\Date\DateHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
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
            try {
                $dateHandler = new DateHandler($formData['date'], $formData['timezone']);
            }
            catch (\Exception $e) {
                $form->addError(new FormError("Unable to create date, verify fields"));
            }
        }

        return $this->render('date/index.html.twig', [
            'form'            => $form->createView(),
            'dateHandler'    => $dateHandler ?? false,
        ]);
    }
}
