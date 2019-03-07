<?php
namespace Asm\Customapi\Api;
 
interface AreaInterface
{
    /**
     * Returns greeting message to user
     *
     * @api
     * @param string $name Users name.
     * @return string Greeting message with users name.
     */
    public function area();
}