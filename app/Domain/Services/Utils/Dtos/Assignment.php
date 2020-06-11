<?php

namespace App\Domain\Services\Utils\Dtos;

/**
 * DTO Class that represents the Assignment of an image to a user.
 */
class Assignment
{

    /**
     * Users that may have been previously assigned in compatible tasks
     *
     * @var int $recurring
     */
    public int $image;

    /**
     * New users to the current task in context.
     *
     * @var int $user
     */
    public int $user;

    /**
     * Assignment constructor.
     *
     * @param  int  $image
     * @param  int  $user
     */
    public function __construct(int $image, int $user)
    {
        $this->image = $image;
        $this->user = $user;
    }

}
