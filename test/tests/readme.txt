nameing conventeions:

so tests are formed as follows:
SomeHumanReadableTitle.[tag.[tag.[...]]].class.php

every file ending on .class.php are considered a test file, and are included in atleast testAll
They should all run in a proper setup (no special configuration for at test).

testsuites cannot be dependant on each others (they should run individually)

following tags exists (add more if necesary):

rpc:
    these tests uses the rpc protocol in testing

system:
    these tests systemintegration. beware, these cannot test anything application specific.
    They should further all run commandline.

    this is due to the fact, that they are used on production setups, to verify integration

    All special features used (non trivial PHP plugins) should have such a test.