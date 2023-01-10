<?php

namespace App\Dto;

final class CreateOrderInput
{
    public string $reservationIri;
    /**
     * @var array<DishOrderInput>
     */
    public array $dishOrders;
}