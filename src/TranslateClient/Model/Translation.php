<?php

namespace AbstractBabel\TranslateClient\Model;

final class Translation
{
    private $abstract;
    private $language;

    /**
     * @internal
     */
    public function __construct(
        string $abstract,
        string $language
    ) {
        $this->abstract = $abstract;
        $this->language = $language;
    }

    public function getAbstract() : string
    {
        return $this->abstract;
    }

    public function getLanguage() : string
    {
        return $this->language;
    }
}
