<?php
namespace Core\AdBundle\Form;

use Symfony\Component\Form\DataTransformerInterface;

class TagsTransformer implements DataTransformerInterface
{
    private $tagManager;

    public function __construct($tagManager)
    {
        $this->tagManager = $tagManager;
    }

    public function transform($tags)
    {
        return join(',', $tags->toArray());
    }

    public function reverseTransform($tags)
    {
        $tags = strtolower($tags);
        $tags = preg_replace('~\W~u', ',', $tags);

        return $this->tagManager->loadOrCreateTags(
            $this->tagManager->splitTagNames($tags, ',')
        );
    }
}