<?php


class AppExceptions extends Exception
{

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function showExceptionView($message = "")
    {
        View::set_error('error', self::getMessage());
        include_once "views/error/error.view.php";
    }

    public function showMessage()
    {
        View::set_error('error', $this->getMessage());
        include_once "views/error/error.view.php";
    }

}