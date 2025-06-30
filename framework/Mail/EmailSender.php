<?php

namespace PhpMvc\Framework\Mail;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailSender
{
    private PHPMailer $mailer;
    private string $fromEmail;
    private string $fromName;

    public function __construct()
    {
        $this->mailer = new PHPMailer(true);
        $this->fromEmail = env('SMTP_USERNAME') ?? 'no-reply@example.com';
        $this->fromName = env('SMTP_FROM_NAME') ?? 'App Name';

        // Configuración del servidor SMTP
        try {
            $this->mailer->isSMTP();
            $this->mailer->Host = env('SMTP_HOST') ?? 'smtp.gmail.com';
            $this->mailer->SMTPAuth = env('SMTP_AUTH') ?? true;
            $this->mailer->Username = env('SMTP_USERNAME') ?? '';
            $this->mailer->Password = env('SMTP_PASSWORD') ?? '';
            $this->mailer->SMTPSecure = env('SMTP_SECURE') !== 'none' ? PHPMailer::ENCRYPTION_STARTTLS : null;
            $this->mailer->Port = (int) (env('SMTP_PORT') ?? 587);

            // Configuración del remitente
            $this->mailer->setFrom($this->fromEmail, $this->fromName);
            $this->mailer->CharSet = 'UTF-8';
        } catch (Exception $e) {
            throw new Exception("Error al configurar el mailer: {$e->getMessage()}");
        }
    }

    /**
     * Envía un correo electrónico.
     *
     * @param string $to Dirección de correo del destinatario
     * @param string $subject Asunto del correo
     * @param string $body Cuerpo del correo (puede ser HTML)
     * @param bool $isHtml Indica si el cuerpo es HTML
     * @return bool
     * @throws Exception
     */
    public function send(string $to, string $subject, string $body, bool $isHtml = false): bool
    {
        try {
            $this->mailer->addAddress($to);
            $this->mailer->isHTML($isHtml);
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $body;

            // Si no es HTML, configurar texto plano
            if (!$isHtml) {
                $this->mailer->AltBody = strip_tags($body);
            }

            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            throw new Exception("Error al enviar el correo: {$e->getMessage()}");
        } finally {
            $this->mailer->clearAddresses();
        }
    }

    public function sendFrom(string $fromEmail, string $fromName, string $to, string $subject, string $body, bool $isHtml = false)
    {
        $this->mailer->setFrom($fromEmail, $fromName);
        $returnValue = $this->send($to, $subject, $body, $isHtml);
        $this->mailer->setFrom($this->fromEmail, $this->fromName);

        return $returnValue;
    }
}
