<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "ANNOTATION"})
 */
class UniqueName extends Constraint
{
    public $message = 'el valor "{{ value }}" ya existe en la base de datos.';
}
