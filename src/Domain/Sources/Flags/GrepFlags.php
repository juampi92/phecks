<?php

namespace Juampi92\Phecks\Domain\Sources\Flags;

class GrepFlags
{
    /** @var string Read  all  files under each directory, recursively. Follow all symbolic links, unlike -r */
    public const DEFERENCE_RECURSIVE = '-R';

    /** @var string Print the file name for each match.  This  is  the  default when there is more than one file to search. */
    public const WITH_FILENAME = '-H';

    /** @var string Prefix each line of output with  the  1-based  line  number within its input file. */
    public const LINE_NUMBER = '-n';

    /** @var string Ignore  case  distinctions,  so that characters that differ only in case match each other. */
    public const IGNORE_CASE = '-i';

    /** @var string Interpret PATTERNS as extended regular expressions (EREs, see below). */
    public const EXTENDED_REGEXP = '-E';

    /** @var string Interpret PATTERNS as basic regular expressions (BREs, see below). This is the default. */
    public const BASIC_REGEXP = '-G';

    /** @var string Interpret PATTERNS as Perl-compatible regular expressions (PCREs). This option is experimental when combined with the -z (--null-data) option, and grep -P may warn of unimplemented features. */
    public const PERL_REGEXP = '-P';

    /** @var string Interpret PATTERNS as fixed strings, not regular expressions. */
    public const FIXED_STRINGS = '-F';
}
