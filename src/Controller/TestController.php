<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     * @Route("/test", name="test")
     */
    public function index()
    {
        $table = [];

        for ($i=0; $i<10; $i++){
            $table[] = ['ligne 1',
                'ligne 2'];
        }

        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
            'table' => $table
        ]);
    }
}
