<?php 

class CustomerParser {

    public static $customers = array();

    public static function parseCustomers(string $fileContent) {

        try {

            $lines = explode("\n", $fileContent);

            // Check the number of columns is correct
            for ($i = 1; $i < count($lines); $i++) {
                $col =  explode(',', $lines[$i]);

                if(count($col) != 2) throw new Exception("Invalid data at row".($i + 1));

                $nc = new Customer;

                $nc->setUserID($col[0]);
                $nc->setAddress($col[1]);

                self::$customers[] = $nc;

            }

            return self::$customers;

        } catch (Exception $ex){
            echo $ex.getMessage();
        }

    }


}


?>