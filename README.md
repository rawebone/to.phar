# to.phar -- A PHAR (PHP ARchive) Compiler

to.phar is a small and simple command line utility written in PHP which allows
users to create [Phar](http://www.php.net/manual/en/book.phar.php) archives,
either in an 'executable' or compressed format.

Usage
-----

    to.phar [options]

    Options:
        -o, --output        The name of the PHAR archive to be output
        -s, --stub          The stub file to act as the boot loader for your library or application
        -c, --compression   The level of compression to be applied to the archive (none|bzip|tar)
        -e, --encryption    Encryption to be applied to the archive (none|MD5|SHA1|SHA256|SHA512|OPENSSL)
        -k, --keyfile       If using -e OPENSSL, a private key file must be specified
        -f, --filelist      Comma-Seperated list of files to be added to the archive
        -a, --alias         Internal PHAR alias

    Licence:
        to.phar is released under the MIT licence, a copy of which should have been included with this program.
        If a copy was not included please view it online at http://www.opensource.org/licenses/MIT

It is important to note that, at present, the '-f' option does not support
wildcards. This is on the bug list for the future when I have more time to look
at it.

Why
---

I've seen Phar compilation being implemented in objects in lots of different
PHP projects, which seems like an awful waste of time to repeat constantly for
something which should be relatively straightforward. Further, I wanted a 
compilation step which could be embedded in Makefiles or build scripts.

Project Status & Building
-------------------------

The project is currently suffering from a problem self compiling, but this shouldn't
take too long to resolve.

To make your own copy from source, do the following:

    cd ./src
    chmod +x to.phar.php
    php to.phar.php -o ../builds/to.phar -s stub.php -a to.phar -f to.phar.php,lib/ToPhar/CommandLine.php
    chmod +x ../builds/to.phar
