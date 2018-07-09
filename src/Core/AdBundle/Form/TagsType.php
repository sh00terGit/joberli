<?php
namespace Core\AdBundle\Form;

use FPN\TagBundle\Entity\TagManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TagsType extends AbstractType
{
    public function __construct(TagManager $tagManager)
    {
        $this->tagManager = $tagManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new TagsTransformer($this->tagManager);
        $builder->addModelTransformer($transformer);
    }

    public function getParent()
    {
        return 'text';
    }

    public function getName()
    {
        return 'tags_entry';
    }
}