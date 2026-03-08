<?php

require_once BASE_PATH . '/app/middlewares/Middleware.php';
require_once BASE_PATH . '/app/models/NotificationModels.php';

class NotificationController
{
    private $notificationModel;

    public function __construct()
    {
        Middleware::check(); // hanya cek login

        $this->notificationModel = new NotificationModel();
    }

    public function read()
    {
        $notifId = $_GET['id'] ?? null;
        $redirect = $_GET['redirect'] ?? BASE_URL;


        if (!$notifId) {
            header('Location: ' . BASE_URL);
            exit;
        }

        $this->notificationModel->markAsRead($notifId);

        // kembali ke halaman sebelumnya
        header('Location: ' . $redirect);
        exit;
    }
}