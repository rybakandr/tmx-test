<?php

namespace app\Core;

class InputPrompter
{
    public static function prompt(string $attributeName): int
    {
        $correctNumber = false;
        while (!$correctNumber){
            $input = readline("Enter $attributeName (must be positive integer): ");
            if(preg_match("/^[0-9]+$/", $input) && intval($input) > 0){
                $correctNumber = true;
            }
        }
        return $input;
    }
}