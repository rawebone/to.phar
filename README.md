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

There are other methods of creating Phar files. There's [Phar-util](https://github.com/koto/phar-util)
for example, or from PHP5.3 the 'phar' commandline utility but these are either
too much, or about as pleasurable as dancing barefoot on glass.

Project Status & Building
-------------------------

The project is self hosting, i.e. you can compile this from source using itself. Of
course, typing out a lot of commands to build and test it doesn't at all appeal
so I have created an ant build script to handle this.

To make your own copy from source, do the following:

    ant make

If you do want to get hands on with the command line, you can use the build
script to get the commands to be run. It's all laid out for you.
