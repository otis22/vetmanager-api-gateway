<?php

declare(strict_types=1);

namespace VetmanagerApiGateway\DTO\Pet;

use VetmanagerApiGateway\DTO\Breed\BreedOnlyDto;
use VetmanagerApiGateway\DTO\PetType\PetTypeOnlyDto;

class PetAdditionalPlusTypeAndBreedDto extends PetOnlyDto
{
    /**
     * @param int|null $id
     * @param int|null $owner_id
     * @param int|null $type_id
     * @param string|null $alias
     * @param string|null $sex
     * @param string|null $date_register
     * @param string|null $birthday
     * @param string|null $note
     * @param int|null $breed_id
     * @param int|null $old_id
     * @param int|null $color_id
     * @param string|null $deathnote
     * @param string|null $deathdate
     * @param string|null $chip_number
     * @param string|null $lab_number
     * @param string|null $status
     * @param string|null $picture
     * @param string|null $weight
     * @param string|null $edit_date
     * @param PetTypeOnlyDto|null $pet_type_data
     * @param BreedOnlyDto|null $breed_data
     */
    public function __construct(
        protected ?int            $id,
        protected ?int            $owner_id,
        protected ?int            $type_id,
        protected ?string         $alias,
        protected ?string         $sex,
        protected ?string         $date_register,
        protected ?string         $birthday,
        protected ?string         $note,
        protected ?int            $breed_id,
        protected ?int            $old_id,
        protected ?int            $color_id,
        protected ?string         $deathnote,
        protected ?string         $deathdate,
        protected ?string         $chip_number,
        protected ?string         $lab_number,
        protected ?string         $status,
        protected ?string         $picture,
        protected ?string         $weight,
        protected ?string         $edit_date,
        protected ?PetTypeOnlyDto $pet_type_data = null,
        protected ?BreedOnlyDto   $breed_data = null
    )
    {
        parent::__construct(
            $id,
            $owner_id,
            $type_id,
            $alias,
            $sex,
            $date_register,
            $birthday,
            $note,
            $breed_id,
            $old_id,
            $color_id,
            $deathnote,
            $deathdate,
            $chip_number,
            $lab_number,
            $status,
            $picture,
            $weight,
            $edit_date
        );
    }

    public function getPetTypeOnlyDto(): ?PetTypeOnlyDto
    {
        return $this->pet_type_data;
    }

    public function getBreedOnlyDto(): ?BreedOnlyDto
    {
        return $this->breed_data;
    }

}
