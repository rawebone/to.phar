#!/usr/bin/php -dphar.readonly=0
<?php
/**
 * to.phar
 * =======
 *
 * Copyright (c) 2011 Nick Rawe
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files
 * (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge,
 * publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE
 * FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 * @license http://www.opensource.org/licenses/MIT
 * @author Nick Rawe
 */

set_exception_handler(function ($e) {
    echo "Fatal Error: an exception was encountered. Details {$e->getMessage()}\n\n";
    exit(-1);
});

set_error_handler(function ($id, $msg, $file, $line, $context) {
    echo "Error: $msg\n";
}, E_ALL);

try
{
    Phar::mapPhar();
    require_once "phar://to.phar/to.phar.php";    
}
catch (\PharException $e)
{
    "to.phar cannot continue as an exception occured while loading. Message: " . $e->getMessage();
}

__HALT_COMPILER();
?>