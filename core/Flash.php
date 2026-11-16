<?php
class Flash
{
    public static function set(string $type, string $message): void
    {
        Session::set('_flash', ['type' => $type, 'message' => $message]);
    }

    public static function get(): ?array
    {
        $flash = Session::get('_flash');
        Session::forget('_flash');
        return $flash;
    }
}
