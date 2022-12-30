<?php

namespace App\Model\DataObject;

class Demande
{
    private string $role;
    private Question $question;
    private User $user;
    private ?Proposition $proposition;

    public function __construct(
        string $type,
        Question $question,
        User $user,
        ?Proposition $proposition=null
    )
{
    $this->role = $type;
    $this->question=$question;
    $this->user=$user;
    $this->proposition=$proposition;
}

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @return string
     */
    public function getQuestion(): Question
    {
        return $this->question;
    }

    /**
     * @return string
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return string|null
     */
    public function getProposition(): ?Proposition
    {
        return $this->proposition;
    }

}