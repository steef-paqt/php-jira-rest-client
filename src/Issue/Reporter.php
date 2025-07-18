<?php

namespace JiraRestApi\Issue;

use JiraRestApi\ClassSerialize;

class Reporter implements \JsonSerializable
{
    use ClassSerialize;

    public ?string $key = null;

    public ?string $timeZone = null;

    public ?string $self = null;

    public ?string $name = null;

    public ?string $emailAddress = null;

    public ?array $avatarUrls = null;

    public ?string $displayName = null;

    public ?bool $active = null;

    // want assignee to unassigned
    private bool $wantUnassigned = false;

    public ?string $accountId = null;

    public ?string $locale = null;

    public ?array $groups = null;

    public ?array $applicationRoles = null;

    public ?string $expand = null;

    public ?bool $deleted = null;

    public ?string $accountType = null;

    #[\ReturnTypeWillChange]
    public function jsonSerialize(): ?array
    {
        $vars = get_object_vars($this);

        foreach ($vars as $key => $value) {
            if ($key === 'name' && ($this->isWantUnassigned() === true)) {
                continue;
            }
            if ($key === 'wantUnassigned') {
                unset($vars[$key]);
            } elseif (is_null($value) || $value === '') {
                unset($vars[$key]);
            }
        }

        if (empty($vars)) {
            return null;
        }

        return $vars;
    }

    /**
     * determine class has value for effective json serialize.
     *
     * @see https://github.com/lesstif/php-jira-rest-client/issues/126
     *
     * @return bool
     */
    public function isEmpty()
    {
        return (empty($this->name) && empty($this->self));
    }

    /**
     * @return bool
     */
    public function isWantUnassigned()
    {
        return $this->wantUnassigned;
    }

    /**
     * @param bool $param boolean
     */
    public function setWantUnassigned(bool $param)
    {
        $this->wantUnassigned = $param;
        $this->name = null;
    }
}
