<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "ANNOTATION"})
 */
class UniqueCode extends Constraint
{

    public $message = 'el valor "{{ value }}" ya existe en la base de datos.';
}
