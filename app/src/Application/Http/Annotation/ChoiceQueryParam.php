<?php

declare(strict_types=1);

namespace App\Application\Http\Annotation;

use App\Application\Utils\ArrayUtils;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Choice;

/**
 * Represents a parameter that must be present in GET data.
 * Automatically generates description with valid items from Choices constraints
 *
 * @Annotation
 * @Target({"CLASS", "METHOD"})
 */
class ChoiceQueryParam extends QueryParam
{
    public function getDefault()
    {
        // because of the API doc don't using description getter
        $this->mutateDescriptionField();

        return parent::getDefault();
    }

    protected function mutateDescriptionField()
    {
        if ($this->description) {
            return;
        }

        $choices = [];

        foreach ($this->getConstraints() as $constraint) {
            if (!$constraint instanceof Constraint) {
                continue;
            }

            if ($constraint instanceof All) {
                foreach ($constraint->constraints as $internalConstraint) {
                    if (!$internalConstraint instanceof Choice) {
                        continue;
                    }

                    $choices[] = $this->getChoices($internalConstraint);
                }
            }

            if ($constraint instanceof Choice) {
                $choices[] = $this->getChoices($constraint);
            }
        }

        if ($choices) {
            $choices = ArrayUtils::flatten($choices, false);

            $this->description = implode(' | ', $choices);
        }
    }

    private function getChoices(Choice $constraint): array
    {
        if ($constraint->callback) {
            return $constraint->callback();
        }

        return $constraint->choices;
    }
}
