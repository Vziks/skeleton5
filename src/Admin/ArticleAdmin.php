<?php

declare(strict_types = 1);

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\Form\Type\DateTimePickerType;
use Sonata\MediaBundle\Form\Type\MediaType;

final class ArticleAdmin extends AbstractAdmin
{
    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('id')
            ->add('name')
            ->add('description')
            ->add('text')
            ->add('datePublication');
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->addIdentifier('id')
            ->add('name', null, ['editable' => true])
            ->add('datePublication')
            ->add(ListMapper::NAME_ACTIONS, null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->add('name')
            ->add('description')
            ->add(
                'image',
                MediaType::class,
                [
                    'required' => true,
                    'context' => 'default',
                    'provider' => 'sonata.media.provider.image',
                    //                'help' => '<i class="glyphicon glyphicon-info-sign"></i> <span style="color:orange">Картинка с шириной не меньше ' . $this->newsWidth . ' px и размером не больше ' . $this->formatBytes($this->maxFileSizeBytes, 0) . '</span>',
                ]
            )
            ->add('text')
            ->add('datePublication');
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('id')
            ->add('name')
            ->add('description')
            ->add('text')
            ->add('datePublication');
    }
}
