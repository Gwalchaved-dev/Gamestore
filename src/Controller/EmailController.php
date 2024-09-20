<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class EmailController extends AbstractController
{
    #[Route('/send-email', name: 'send_email')]
    public function sendEmail(MailerInterface $mailer): Response
    {
        $email = (new Email())
            ->from('test@example.com') // L'adresse expéditrice
            ->to('recipient@example.com')    // L'adresse du destinataire
            ->subject('Hello from Mailtrap!')
            ->text('This is a test email sent using Mailtrap.');

        $mailer->send($email);

        return new Response('Email sent successfully!');
    }
}
