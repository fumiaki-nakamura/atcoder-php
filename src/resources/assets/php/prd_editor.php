<?php

class AtCoderStream
{
    public function readLine($format)
    {
        return fscanf(STDIN, $format);
    }

    public function writeLine($format)
    {
        echo $format, "\n";
    }
}

// export
$stream = new AtCoderStream();
