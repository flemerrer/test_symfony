<?php

    namespace App\Models;

    use App\Validator\UniqueCategoryName;
    use Symfony\Component\Validator\Constraints as Assert;

    class CreateCategoryDTO
    {
        public function __construct(
            #[UniqueCategoryName()]
            #[Assert\NotBlank]
            #[Assert\Length(min: 2, max: 180)]
            public readonly string $name,
        )
        {
        }
    }
