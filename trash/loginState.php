<?php
/**
 * Created by IntelliJ IDEA.
 * User: johna
 * Date: 2018-10-17
 * Time: 11:05
 */

namespace model;


class loginState {
    private $loggedInState;
    private $stateCode;

    /**
     * loginState constructor.
     * @param bool $loggedIn
     * @param int $code
     */
    public function __construct(bool $loggedIn, int $code) {
        $this->loggedInState = $loggedIn;
        $this->stateCode = $code;
    }

    public function getloggedInState(): bool {
        return $this->loggedInState;
    }

    public function getStateCode(): int {
        return $this->stateCode;
    }
}