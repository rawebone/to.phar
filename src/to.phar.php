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

//
//  Startup Checks
//

if (PHP_SAPI != "cli")
{
    throw new Exception("tophar cannot be run outside of the commandline");
}

//  Ubuntu distributions contain additional information about the version
$phpVersion = (strpos(PHP_VERSION, "-") !== false ? substr(PHP_VERSION, 0, strpos(PHP_VERSION, "-")) : PHP_VERSION);
if (version_compare($phpVersion, "5.3.0") < 0)
{
    throw new Exception("your version of PHP ($phpVersion) is not supported");
}
unset($phpVersion);

if ($argc < 2)
{
    echo <<<HELP
to.phar -- a PHAR (PHP ARchive) Compiler

Usage:
    to.phar [options]

Options:
    -o, --output        The name of the PHAR archive to be output
    -s, --stub          The stub file to act as the boot loader for your library or application
    -c, --compression   The level of compression to be applied to the archive (none|bzip|tar)
    -e, --encryption    Encryption to be applied to the archive (none|MD5|SHA1|SHA256|SHA512|OPENSSL)
    -k, --keyfile       If using -e OPENSSL, a private key file must be specified,
    -f, --filelist      Comma-Seperated list of files to be added to the archive,
    -a, --alias         Internal File Alias

Licence:
    to.phar is released under the MIT licence, a copy of which should have been included with this program.
    If a copy was not included please view it online at http://www.opensource.org/licenses/MIT

HELP;
    exit(0);
}

if (!class_exists("lib/ToPhar/CommandLine.php"))
{
    require_once "lib/ToPhar/CommandLine.php";
}

//  Process Command Line
$cmd = new \ToPhar\CommandLine();
$cmd->addPair("o", "output", true);
$cmd->addPair("s", "stub", true);
$cmd->addPair("c", "compression", true);
$cmd->addPair("e", "encryption", true);
$cmd->addPair("k", "keyfile", true);
$cmd->addPair("f", "filelist", true);
$cmd->addPair("a", "alias", true);
$cmd->match();

$output = trim($cmd->getArg("o"));
if (file_exists($output))
{
    unlink($output);
}

$alias   = ($cmd->getArg("a") === null ? $cmd->getArg("a") : pathinfo($output, PATHINFO_FILENAME));
$archive = new Phar($output, 0, $alias);
$archive->startBuffering();

//
//  Stub
//

$stub = trim($cmd->getArg("s"));
if ($stub && file_exists($stub))
{
    $stubText = file_get_contents($stub);
    if (is_null($stubText) || empty($stubText))
    {
        echo "Warning: the stub file requested is not valid\n";         
    }
    else
    {
        $archive->setStub($stubText);
    }
}
elseif ($stub && !file_exists($stub))
{
    echo "Warning: A stub file is specified, but does not exist\n";
}

//
//  Encryption
//

$encryption = $cmd->getArg("e");
if ($encryption)
{
    switch ($encryption)
    {
        case "none":
            break;
        case "MD5":
            $archive->setSignatureAlgorithm(Phar::MD5);
            break;
        case "SHA1":
            $archive->setSignatureAlgorithm(Phar::SHA1);
            break;
        case "SHA256":
            $archive->setSignatureAlgorithm(Phar::SHA256);
            break;
        case "SHA512":
            $archive->setSignatureAlgorithm(Phar::SHA512);
            break;
        case "OPENSSL":
            $keyfile = $cmd->getArg("k");
            if ($keyfile === null || !file_exists($keyfile))
            {
                echo "Warning: Using an ecryption level of OPENSSL requires a valid keyfile to also be specified\n";
                break;
            }
            $archive->setSignatureAlgorithm(Phar::OPENSSL, $keyfile);
            break;
        default:
            echo "Warning: A level of encryption has been specified, but is invalid\n";
    }
}

//
//  Compression
//

$compression = $cmd->getArg("c");
if ($compression)
{
    switch ($compression)
    {
        case "none":
            break;
        case "bzip":
            $archive->compress(Phar::BZ2);
            break;
        case "tar":
            $archive->compress(Phar::GZ);
            break;
        default:
            echo "Warning: A level of compression of was specified, but is invalid\n";
    }
}

//
//  Process the files to be added to the archive
//

$files = $cmd->getArg("f");
if ($files === null)
{
    echo "Warning: no files have been specified for adding to the library\n";
}
else
{
    foreach (explode(",", $files) as $file)
    {
        $file = trim($file);
        if (empty($file) || !file_exists($file))
        {
            echo "Warning: file '$file' does not exist\n";
            continue;
        }
        $archive[$file] = file_get_contents($file);
    }
}

$archive->stopBuffering();
?>
