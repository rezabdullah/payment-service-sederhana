<?php

namespace Rezabdullah\Helper;

class Cli
{
    public static function parsingArgv(): ?array
    {
        global $argv;
        
        if(isset($argv) && !empty($argv)) {
            $temp = [];

            foreach ($argv as $key => $value) 
            {
                if($key == "0") continue;
            
                $expl = explode("=", strtolower($value), 2);

                $temp[$expl[0]] = $expl[1];
            }

            return $temp;
        }

        return null;
    }
}