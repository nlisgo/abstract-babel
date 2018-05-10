<?php

namespace AbstractBabel\Babel\Controller;

use AbstractBabel\CrossRefClient\ApiSdk as CrossRefSdk;
use AbstractBabel\Babel\ApiResponse;
use AbstractBabel\CrossRefClient\Model\Work;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class BabelController
{
    private $crossRefSdk;

    public function __construct(CrossRefSdk $crossRefSdk)
    {
        $this->crossRefSdk = $crossRefSdk;
    }

    public function babelAction(Request $request) : Response
    {
        // Retrieve query parameters.
        $doi = $request->query->get('doi');

        // Verify that doi parameter is present.
        if (!is_string($doi) || empty($doi)) {
            throw new BadRequestHttpException('Missing doi value');
        }

        // Perform query to CrossRef API.
        $content = $this->crossRefSdk->works()->get($doi)
            ->then(function (Work $work) {
                return [
                    'abstract' => $work->getAbstract(),
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
