<?php

namespace AbstractBabel\Babel\Controller;

use AbstractBabel\CrossRefClient\CrossRefSdk;
use AbstractBabel\Babel\ApiResponse;
use AbstractBabel\CrossRefClient\Model\Work;
use AbstractBabel\TranslateClient\TranslateSdk;
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

        // Verify that $doi parameter is present.
        if (!is_string($doi) || empty($doi)) {
            throw new BadRequestHttpException('Missing doi value');
        }

        // Verify that $to parameter is present.
        if (!is_string($to) || empty($to)) {
            throw new BadRequestHttpException('Missing to value');
        }

        // Perform query to CrossRef API.
        $content = $this->crossRefSdk->works()->get($doi)
            ->then(function (Work $work) use ($to, $from) {
                $translation = $this->translateSdk->translate()->get($work->getAbstract(), $to, $from);
                return [
                    'abstract' => $translation->getAbstract(),
                ];
            })->wait();

        // Set Content-Type.
        $headers = ['Content-Type' => 'application/json'];

        return new ApiResponse(
            $content,
            Response::HTTP_OK,
            $headers
        );
    }
}
