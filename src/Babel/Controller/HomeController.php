<?php

namespace AbstractBabel\Babel\Controller;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Twig_Environment;

final class HomeController
{
    private $twig;
    private $babelController;

    public function __construct(Twig_Environment $twig, BabelController $babelController)
    {
        $this->twig = $twig;
        $this->babelController = $babelController;
    }

    public function homeAction(Request $request)
    {
        $doi = $request->get('doi');
        $from = $request->get('from', 'en');
        $to = $request->get('to');
        $abstract = null;
        $translation = null;

        if ($doi && $from && $to) {
            try {
                $babel = json_decode($this->babelController->babelAction($request)->getContent());
                $abstract = $babel->original;
                $translation = $babel->abstract;
            } catch (Exception $e) {}
        }

        return $this->twig->render('home.html.twig', [
            'doi' => $request->get('doi'),
            'from' => $request->get('from', 'en'),
            'to' => $request->get('to'),
            'abstract' => $abstract,
            'translation' => $translation,
        ]);
    }
}
