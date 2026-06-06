<?php

class OwnerController {
    public function dashboard(): void {
        Auth::requireOwner();
        require APP_PATH . '/Views/owner/dashboard.php';
    }
}
