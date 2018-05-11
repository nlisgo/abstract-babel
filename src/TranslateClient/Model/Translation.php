<?php

namespace AbstractBabel\TranslateClient\Model;

final class Translation
{
    private $original;
    private $abstract;
    private $language;

    /**
     * @internal
     */
    public function __construct(
        string $original,
        string $abstract,
        string $language
    ) {
        $this->original = $original;
        $this->abstract = $abstract;
        $this->language = $language;
    }

    public function getOriginal() : string
    {
        return $this->original;
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
