<?php

namespace App\Controllers;

use PhpMvc\Framework\Http\Request;
use PhpMvc\Framework\Mail\EmailSender;

class ContactController
{
    public function index()
    {
        return view('contact.index');
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $this->sendContactEmail($data);

        redirect('contact.index')->with('flash', 'Mensaje enviado correctamente.');
    }

    /**
     * Validates the contact form data.
     * TODO: incorporar un validate al Request cuando incorpore ajax
     * @param array $data
     * @throws \InvalidArgumentException
     */
    private function validate(array $data)
    {
        if (empty($data['name']) || empty($data['email']) || empty($data['message'])) {
            throw new \InvalidArgumentException('Todos los campos son obligatorios.');
        }
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('El correo electrónico no es válido.');
        }
        // Otras validaciones según sea necesario
    }

    /**
     * Sends the contact email.
     *
     * @param array $data
     */
    private function sendContactEmail(array $data)
    {
        $emailSender = new EmailSender();
        $adminEmail = env('APP_ADMIN_EMAIL', 'admin@example.com');

        $emailSender->addReplyTo($data['email'], $data['name']);
        return $emailSender->send($adminEmail, 'Mensaje de Cliente', $data['message'], true);
    }
}
