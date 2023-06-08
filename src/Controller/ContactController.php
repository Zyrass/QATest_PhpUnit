<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Contact;
use App\Form\ContactType;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function contactForm(Request $request)
    {
        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Process the form data (e.g., save to the database)

            // Redirect to a success page with a personalized greeting
            $name = $contact->getName();
            return $this->redirectToRoute('success', ['name' => $name]);
        }

        return $this->render('contact/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/success/{name}", name="success")
     */
    public function success($name)
    {
        return $this->render('contact/success.html.twig', [
            'name' => $name,
        ]);
    }
}
