<?php 
namespace src\Controller;

class ErrorController extends AbstractController {
    public function show(\Exception $e) {
        return [
            'error' => true,
            'message' => $e->getMessage()
        ];
    }
}