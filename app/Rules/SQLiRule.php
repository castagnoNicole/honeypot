<?php

namespace App\Rules;

use App\Events\SQLinjectionAttempted;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;

class SQLiRule implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $sql_injection_payloads = array(
            "OR 1=1",
            "OR 1=0",
            "OR x=x",
            "OR x=y",
            "OR 1=1#",
            "OR 1=0#",
            "OR x=x#",
            "OR x=y#",
            "OR 1=1--",
            "OR 1=0--",
            "OR x=x--",
            "OR x=y--",
            "OR 3409=3409 AND ('pytW' LIKE 'pytW",
            "OR 3409=3409 AND ('pytW' LIKE 'pytY",
            "HAVING 1=1",
            "HAVING 1=0",
            "HAVING 1=1#",
            "HAVING 1=0#",
            "HAVING 1=1--",
            "HAVING 1=0--",
            "AND 1=1",
            "AND 1=0",
            "AND 1=1--",
            "AND 1=0--",
            "AND 1=1#",
            "AND 1=0#",
            "AND 1=1 AND '%'='",
            "AND 1=0 AND '%'='",
            "AND 1083=1083 AND (1427=1427",
            "AND 7506=9091 AND (5913=5913",
            "AND 1083=1083 AND ('1427=1427",
            "AND 7506=9091 AND ('5913=5913",
            "AND 7300=7300 AND 'pKlZ'='pKlZ",
            "AND 7300=7300 AND 'pKlZ'='pKlY",
            "AND 7300=7300 AND ('pKlZ'='pKlZ",
            "AND 7300=7300 AND ('pKlZ'='pKlY",
            "AS INJECTX WHERE 1=1 AND 1=1",
            "AS INJECTX WHERE 1=1 AND 1=0",
            "AS INJECTX WHERE 1=1 AND 1=1#",
            "AS INJECTX WHERE 1=1 AND 1=0#",
            "AS INJECTX WHERE 1=1 AND 1=1--",
            "AS INJECTX WHERE 1=1 AND 1=0--",
            "WHERE 1=1 AND 1=1",
            "WHERE 1=1 AND 1=0",
            "WHERE 1=1 AND 1=1#",
            "WHERE 1=1 AND 1=0#",
            "WHERE 1=1 AND 1=1--",
            "WHERE 1=1 AND 1=0--",
            "ORDER BY 1--",
            "ORDER BY 2--",
            "ORDER BY 3--",
            "ORDER BY 4--",
            "ORDER BY 5--",
            "ORDER BY 6--",
            "ORDER BY 7--",
            "ORDER BY 8--",
            "ORDER BY 9--",
            "ORDER BY 10--",
            "ORDER BY 11--",
            "ORDER BY 12--",
            "ORDER BY 13--",
            "ORDER BY 14--",
            "ORDER BY 15--",
            "ORDER BY 16--",
            "ORDER BY 17--",
            "ORDER BY 18--",
            "ORDER BY 19--",
            "ORDER BY 20--",
            "ORDER BY 21--",
            "ORDER BY 22--",
            "ORDER BY 23--",
            "ORDER BY 24--",
            "ORDER BY 25--",
            "ORDER BY 26--",
            "ORDER BY 27--",
            "ORDER BY 28--",
            "ORDER BY 29--",
            "ORDER BY 30--",
            "ORDER BY 31337--",
            "ORDER BY 1#",
            "ORDER BY 2#",
            "ORDER BY 3#",
            "ORDER BY 4#",
            "ORDER BY 5#",
            "ORDER BY 6#",
            "ORDER BY 7#",
            "ORDER BY 8#",
            "ORDER BY 9#",
            "ORDER BY 10#",
            "ORDER BY 11#",
            "ORDER BY 12#",
            "ORDER BY 13#",
            "ORDER BY 14#",
            "ORDER BY 15#",
            "ORDER BY 16#",
            "ORDER BY 17#",
            "ORDER BY 18#",
            "ORDER BY 19#",
            "ORDER BY 20#",
            "ORDER BY 21#",
            "ORDER BY 22#",
            "ORDER BY 23#",
            "ORDER BY 24#",
            "ORDER BY 25#",
            "ORDER BY 26#",
            "ORDER BY 27#",
            "ORDER BY 28#",
            "ORDER BY 29#",
            "ORDER BY 30#",
            "ORDER BY 31337#",
            "ORDER BY 1",
            "ORDER BY 2",
            "ORDER BY 3",
            "ORDER BY 4",
            "ORDER BY 5",
            "ORDER BY 6",
            "ORDER BY 7",
            "ORDER BY 8",
            "ORDER BY 9",
            "ORDER BY 10",
            "ORDER BY 11",
            "ORDER BY 12",
            "ORDER BY 13",
            "ORDER BY 14",
            "ORDER BY 15",
            "ORDER BY 16",
            "ORDER BY 17",
            "ORDER BY 18",
            "ORDER BY 19",
            "ORDER BY 20",
            "ORDER BY 21",
            "ORDER BY 22",
            "ORDER BY 23",
            "ORDER BY 24",
            "ORDER BY 25",
            "ORDER BY 26",
            "ORDER BY 27",
            "ORDER BY 28",
            "ORDER BY 29",
            "ORDER BY 30",
            "ORDER BY 31337",
            "RLIKE (SELECT (CASE WHEN (4346=4346) THEN 0x61646d696e ELSE 0x28 END)) AND 'Txws'='",
            "RLIKE (SELECT (CASE WHEN (4346=4347) THEN 0x61646d696e ELSE 0x28 END)) AND 'Txws'='",
            "IF(7423=7424) SELECT 7423 ELSE DROP FUNCTION xcjl--",
            "IF(7423=7423) SELECT 7423 ELSE DROP FUNCTION xcjl--",
            "%' AND 8310=8310 AND '%'='",
            "%' AND 8310=8311 AND '%'='",
            "and (select substring(@@version,1,1))='X'",
            "and (select substring(@@version,1,1))='M'",
            "and (select substring(@@version,2,1))='i'",
            "and (select substring(@@version,2,1))='y'",
            "and (select substring(@@version,3,1))='c'",
            "and (select substring(@@version,3,1))='S'",
            "and (select substring(@@version,3,1))='X'",
            "sleep(5)#",
            "1 or sleep(5)#",
            "\" or sleep(5)#",
            "' or sleep(5)#",
            "\" or sleep(5)=\"",
            "' or sleep(5)='",
            "1) or sleep(5)#",
            "\") or sleep(5)=\"",
            "') or sleep(5)='",
            "1)) or sleep(5)#",
            "\")) or sleep(5)=\"",
            "')) or sleep(5)='",
            ";waitfor delay '0:0:5'--",
            ");waitfor delay '0:0:5'--",
            "';waitfor delay '0:0:5'--",
            "\";waitfor delay '0:0:5'--",
            "');waitfor delay '0:0:5'--",
            "\");waitfor delay '0:0:5'--",
            "));waitfor delay '0:0:5'--",
            "'));waitfor delay '0:0:5'--",
            "\"));waitfor delay '0:0:5'--",
            "benchmark(10000000,MD5(1))#",
            "1 or benchmark(10000000,MD5(1))#",
            "\" or benchmark(10000000,MD5(1))#",
            "' or benchmark(10000000,MD5(1))#",
            "1) or benchmark(10000000,MD5(1))#",
            "\") or benchmark(10000000,MD5(1))#",
            "') or benchmark(10000000,MD5(1))#",
            "1)) or benchmark(10000000,MD5(1))#",
            "\")) or benchmark(10000000,MD5(1))#",
            "')) or benchmark(10000000,MD5(1))#",
            "pg_sleep(5)--",
            "1 or pg_sleep(5)--",
            "\" or pg_sleep(5)--",
            "' or pg_sleep(5)--",
            "1) or pg_sleep(5)--",
            "\") or pg_sleep(5)--",
            "') or pg_sleep(5)--",
            "1)) or pg_sleep(5)--",
            "\")) or pg_sleep(5)--",
            "')) or pg_sleep(5)--",
            "AND (SELECT * FROM (SELECT(SLEEP(5)))bAKL) AND 'vRxe'='vRxe",
            "AND (SELECT * FROM (SELECT(SLEEP(5)))YjoC) AND '%'='",
            "AND (SELECT * FROM (SELECT(SLEEP(5)))nQIP)",
            "AND (SELECT * FROM (SELECT(SLEEP(5)))nQIP)--",
            "AND (SELECT * FROM (SELECT(SLEEP(5)))nQIP)#",
            "SLEEP(5)#",
            "SLEEP(5)--",
            "SLEEP(5)=\"",
            "SLEEP(5)='",
            "or SLEEP(5)",
            "or SLEEP(5)#",
            "or SLEEP(5)--",
            "or SLEEP(5)=\"",
            "or SLEEP(5)='",
            "waitfor delay '00:00:05'",
            "waitfor delay '00:00:05'--",
            "waitfor delay '00:00:05'#",
            "benchmark(50000000,MD5(1))",
            "benchmark(50000000,MD5(1))--",
            "benchmark(50000000,MD5(1))#",
            "or benchmark(50000000,MD5(1))",
            "or benchmark(50000000,MD5(1))--",
            "or benchmark(50000000,MD5(1))#",
            "pg_SLEEP(5)",
            "pg_SLEEP(5)--",
            "pg_SLEEP(5)#",
            "or pg_SLEEP(5)",
            "or pg_SLEEP(5)--",
            "or pg_SLEEP(5)#",
            "'\"",
            "AnD SLEEP(5)",
            "AnD SLEEP(5)--",
            "AnD SLEEP(5)#",
            "&&SLEEP(5)",
            "&&SLEEP(5)--",
            "&&SLEEP(5)#",
            "' AnD SLEEP(5) ANd '1",
            "'&&SLEEP(5)&&'1",
            "ORDER BY SLEEP(5)",
            "ORDER BY SLEEP(5)--",
            "ORDER BY SLEEP(5)#",
            "(SELECT * FROM (SELECT(SLEEP(5)))ecMj)",
            "(SELECT * FROM (SELECT(SLEEP(5)))ecMj)#",
            "(SELECT * FROM (SELECT(SLEEP(5)))ecMj)--",
            "+benchmark(3200,SHA1(1))+'",
            "+ SLEEP(10) + '",
            "RANDOMBLOB(500000000/2)",
            "AND 2947=LIKE('ABCDEFG',UPPER(HEX(RANDOMBLOB(500000000/2))))",
            "OR 2947=LIKE('ABCDEFG',UPPER(HEX(RANDOMBLOB(500000000/2))))",
            "RANDOMBLOB(1000000000/2)",
            "AND 2947=LIKE('ABCDEFG',UPPER(HEX(RANDOMBLOB(1000000000/2))))",
            "OR 2947=LIKE('ABCDEFG',UPPER(HEX(RANDOMBLOB(1000000000/2))))",
            "SLEEP(1)/*' or SLEEP(1) or '\" or SLEEP(1) or \"*/",
            "UNION ALL SELECT 1",
            "UNION ALL SELECT 1,2",
            "UNION ALL SELECT 1,2,3",
            "UNION ALL SELECT 1,2,3,4",
            "UNION ALL SELECT 1,2,3,4,5",
            "UNION ALL SELECT 1,2,3,4,5,6",
            "UNION ALL SELECT 1,2,3,4,5,6,7",
            "UNION ALL SELECT 1,2,3,4,5,6,7,8",
            "UNION ALL SELECT 1,2,3,4,5,6,7,8,9",
            "UNION ALL SELECT 1,2,3,4,5,6,7,8,9,10",
            "UNION ALL SELECT 1,2,3,4,5,6,7,8,9,10,11",
            "UNION ALL SELECT 1,2,3,4,5,6,7,8,9,10,11,12",
            "UNION ALL SELECT 1,2,3,4,5,6,7,8,9,10,11,12,13",
            "UNION ALL SELECT 1,2,3,4,5,6,7,8,9,10,11,12,13,14",
            "UNION ALL SELECT 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15",
            "UNION ALL SELECT 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16",
            "UNION ALL SELECT 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17",
            "UNION ALL SELECT 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18",
            "UNION ALL SELECT 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19",
            "UNION ALL SELECT 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20",
            "UNION ALL SELECT 1#",
            "UNION ALL SELECT 1,2#",
            "UNION ALL SELECT 1,2,3#",
            "UNION ALL SELECT 1,2,3,4#",
            "UNION ALL SELECT 1,2,3,4,5#",
            "UNION ALL SELECT 1,2,3,4,5,6#",
            "UNION ALL SELECT 1,2,3,4,5,6,7#",
            "UNION ALL SELECT 1,2,3,4,5,6,7,8#",
            "UNION ALL SELECT 1,2,3,4,5,6,7,8,9#",
            "UNION ALL SELECT 1,2,3,4,5,6,7,8,9,10#",
            "UNION ALL SELECT 1,2,3,4,5,6,7,8,9,10,11#",
            "UNION ALL SELECT 1,2,3,4,5,6,7,8,9,10,11,12#",
            "UNION ALL SELECT 1,2,3,4,5,6,7,8,9,10,11,12,13#",
            "UNION ALL SELECT 1,2,3,4,5,6,7,8,9,10,11,12,13,14#",
            "UNION ALL SELECT 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15#",
            "UNION ALL SELECT 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16#",
            "UNION ALL SELECT 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17#",
            "UNION ALL SELECT 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18#",
            "UNION ALL SELECT 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19#",
            "UNION ALL SELECT 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20#",
            "UNION SELECT @@VERSION,SLEEP(5),3",
            "UNION SELECT @@VERSION,SLEEP(5),USER(),4",
            "UNION SELECT @@VERSION,SLEEP(5),USER(),BENCHMARK(1000000,MD5('A')),5",
            "UNION SELECT @@VERSION,SLEEP(5),USER(),BENCHMARK(1000000,MD5('A')),5,6",
            "UNION SELECT @@VERSION,SLEEP(5),USER(),BENCHMARK(1000000,MD5('A')),5,6,7",
            "UNION SELECT @@VERSION,SLEEP(5),USER(),BENCHMARK(1000000,MD5('A')),5,6,7,8",
            "UNION SELECT @@VERSION,SLEEP(5),USER(),BENCHMARK(1000000,MD5('A')),5,6,7,8,9",
            "UNION SELECT @@VERSION,SLEEP(5),USER(),BENCHMARK(1000000,MD5('A')),5,6,7,8,9,10",
            "UNION SELECT @@VERSION,SLEEP(5),USER(),BENCHMARK(1000000,MD5('A')),5,6,7,8,9,10,11",
            "UNION SELECT @@VERSION,SLEEP(5),USER(),BENCHMARK(1000000,MD5('A')),5,6,7,8,9,10,11,12",
            "UNION SELECT @@VERSION,SLEEP(5),USER(),BENCHMARK(1000000,MD5('A')),5,6,7,8,9,10,11,12,13",
        );

        if (in_array($value, $sql_injection_payloads)) {
            $fail('The :attribute SQL attack has been detected.');
            event(new SQLinjectionAttempted(auth()->user(), $value));
        }
    }


}