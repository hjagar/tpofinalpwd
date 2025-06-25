<?php

namespace App\Controllers;

use PhpMvc\Framework\Http\Request;

class ContactController
{
    public function index()
    {
        return view('contact.index');
    }

    public function store(Request $request)
    {
        $data = $request->all();

        // Validar los datos del formulario
        $this->validate($data);

        // Procesar el envío del formulario
        $this->sendContactEmail($data);

        // Redirigir o mostrar un mensaje de éxito
        return redirect('contact.index')->with('success', 'Mensaje enviado correctamente.');
    }
    /**
     * Validates the contact form data.
     *
     * @param array $data
     * @throws \InvalidArgumentException
     */
    private function validate(array $data)
    {
        // Aquí se implementaría la lógica de validación
        // Por ejemplo, verificar que los campos requeridos no estén vacíos
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
        // Aquí se implementaría la lógica para enviar el correo electrónico
        // Por ejemplo, usando una librería de envío de correos
        // mail($data['email'], 'Contacto', $data['message']);
    }
}
