<?php
declare(strict_types = 1);
/**
 * /src/Form/Type/Traits/AddBasicFieldToForm.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Form\Type\Traits;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Trait AddBasicFieldToForm
 *
 * @package App\Form\Type\Traits
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
trait AddBasicFieldToForm
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $fields
     */
    protected function addBasicFieldToForm(FormBuilderInterface $builder, array $fields): void
    {
        foreach ($fields as $params) {
            \call_user_func_array([$builder, 'add'], $params);
        }
    }
}
