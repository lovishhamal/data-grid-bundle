<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

namespace EveryWorkflow\DataGridBundle\Tests;

use EveryWorkflow\DataFormBundle\Factory\FieldOptionFactory;
use EveryWorkflow\DataFormBundle\Field\Select\Option;
use EveryWorkflow\DataFormBundle\Model\FormInterface;
use EveryWorkflow\DataFormBundle\Tests\BaseFormTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class BaseGridTestCase extends BaseFormTestCase
{
    public function getExampleUserData(): array
    {
        $userData = [];
        for ($i = 0; $i < 50; ++$i) {
            try {
                $userData[] = [
                    'first_name' => 'Test '.$i,
                    'last_name' => 'Name '.$i,
                    'email' => 'test'.$i.'@example.com',
                    'gender' => random_int(0, 1) ? 'male' : 'female',
                ];
            } catch (\Exception $e) {
            }
        }

        return $userData;
    }

    public function getExampleUserForm(ContainerInterface $container): FormInterface
    {
        $formFieldFactory = $this->getFormFieldFactory($container);
        $formFactory = $this->getFormFactory($formFieldFactory);
        $fieldOptionFactory = new FieldOptionFactory($this->getDataObjectFactory());

        return $formFactory->create([
            $formFieldFactory->createField([
                'label' => 'First name',
                'name' => 'first_name',
            ]),
            $formFieldFactory->createField([
                'label' => 'Last name',
                'name' => 'last_name',
            ]),
            $formFieldFactory->createField([
                'label' => 'Email',
                'name' => 'email',
                'input_type' => 'email',
            ]),
            $formFieldFactory->createField([
                'label' => 'Gender',
                'name' => 'gender',
                'field_type' => 'select_field',
                'options' => [
                    $fieldOptionFactory->create(Option::class, [
                        'key' => 'male',
                        'value' => 'Male',
                    ]),
                    $fieldOptionFactory->create(Option::class, [
                        'key' => 'female',
                        'value' => 'Female',
                    ]),
                    $fieldOptionFactory->create(Option::class, [
                        'key' => 'other',
                        'value' => 'Other',
                    ]),
                ],
            ]),
        ]);
    }
}
