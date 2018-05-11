<?php

namespace AbstractBabel\Babel\Controller;

use AbstractBabel\CrossRefClient\CrossRefSdk;
use AbstractBabel\Babel\ApiResponse;
use AbstractBabel\CrossRefClient\Model\Work;
use AbstractBabel\TranslateClient\Model\Translation;
use AbstractBabel\TranslateClient\TranslateSdk;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class BabelController
{
    private $crossRefSdk;
    private $translateSdk;

    public function __construct(CrossRefSdk $crossRefSdk, TranslateSdk $translateSdk)
    {
        $this->crossRefSdk = $crossRefSdk;
        $this->translateSdk = $translateSdk;
    }

    public function babelAction(Request $request) : Response
    {
        // Retrieve query parameters.
        $doi = $request->query->get('doi');
        $from = $request->query->get('from', 'en');
        $to = $request->query->get('to');
        $checkStore = $request->query->get('check-store', true);

        // Verify that $doi parameter is present.
        if (!is_string($doi) || empty($doi)) {
            throw new BadRequestHttpException('Missing doi value');
        }

        // Verify that $to parameter is present.
        if (!is_string($to) || empty($to)) {
            throw new BadRequestHttpException('Missing to value');
        }

        $translation = null;

        if ($checkStore) {
            try {
                $translation = $this->translateSdk->stored()->get($doi, $to);
            } catch (Exception $e) {}
        }

        // Set Content-Type.
        $headers = ['Content-Type' => 'application/json'];

        return new ApiResponse(
            [
                'abstract' => ($translation ?? $this->requestTranslation($doi, $to, $from))->getAbstract(),
            ],
            Response::HTTP_OK,
            $headers
        );
    }

    private function requestTranslation($doi, $to, $from = 'en') : Translation
    {
        // Perform query to CrossRef API.
        return $this->crossRefSdk->works()->get($doi)
            ->then(function (Work $work) use ($to, $from) {
                return $this->translateSdk->translate()->get($work->getAbstract(), $to, $from);
            })->wait();
    }
}
