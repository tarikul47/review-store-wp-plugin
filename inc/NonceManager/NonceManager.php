<?php
namespace Tarikul\TJMK\Inc\NonceManager;
class NonceManager
{
    public static function create_nonce($action)
    {
        return \wp_create_nonce($action);
    }

    public static function verify_nonce($nonce, $action)
    {
        return \wp_verify_nonce($nonce, $action);
    }
}
