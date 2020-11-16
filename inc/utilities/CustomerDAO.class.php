<?php 

class CustomerDAO {

    // List of Customers
    private static $customers = array();
    private static $users = array();

    // Merged Customer and User list
    private static $mergedList = array();

    // Make a function to get an updated list of customer from the CSV file
    static function refreshCustomers(){

        $customer_data_path = realpath("../../data/customer.data.csv");
        $user_data_path = realpath("../../data/user.data.csv");

        $customer_content = FileService::readfile($customer_data_path);
        self::$customers = CustomerParser::parseCustomers($customer_content);

        $user_content = FileService::readfile($user_data_path);
        self::$users = UserParser::parseUser($user_content);

        // merging customer-pbj and user_obj into a std_obj

        for($i=0; $i < count(self::$users); $i++) {

            foreach(self::$customers as $customer){

                if(self::$users[$i]->getUserID() == $customer->getUserID()){

                    $merged_obj = new stdClass;

                    // Customer Info
                    $merged_obj->userID = $customer->getUserID();
                    $merged_obj->address = $customer->getAddress();

                    // User Info
                    $merged_obj->password = self::$users[$i]->getPassword();
                    $merged_obj->lastName = self::$users[$i]->getLastName();
                    $merged_obj->firstName = self::$users[$i]->getFirstName();
                    $merged_obj->role = self::$users[$i]->getRole();
                    $merged_obj->gender = self::$users[$i]->getGender();
                    $merged_obj->phoneNumber = self::$users[$i]->getPhoneNumber();
                    $merged_obj->email = self::$users[$i]->getEmail();
                    $merged_obj->signUpDate = self::$users[$i]->getSignUpDate();
                    $merged_obj->profilePic = self::$users[$i]->getProfilePic();

                    self::$mergedList[] = $merged_obj;

                }
            }

        }

    }

    public static function getCustomers(){

        self::refreshCustomers();

        return self::$mergedList;

    }

    public static function getCustomerById($id){

        self::refreshCustomers();

        foreach (self::$mergedList as $merged_obj){

            if($merged_obj->userID == $id){
                return $merged_obj;
            }

        }

        $error = new stdClass;
        $error->error = "No user with ID:".$id;

        return $error;

    }

    public static function updateCustomers($profile){

        self::refreshCustomers();

        //Modifying Customer data
        foreach(self::$customers as $customer){
            if($customer->getUserID() == $profile->userID){
                foreach($profile as $prop => $value) {
                    if(property_exists($customer, $prop)){
                        self::setCustomerProperty($customer, $prop, $value);
                    }
                }
            }
        }

        // Modifying user data
        foreach(self::$users as $user){
            if($user->getUserID() == $profile->userID){
                foreach($profile as $prop => $value){
                    if(property_exists($user, $prop)){
                        self::setUserProperty($user, $prop, $value);
                    }
                }
            }
        }

        // Convert each customer into string
        $customer_str = "userID,address";

        foreach(self::$customers as $c){
            $customer_str .= "\n".
            $c->getUserID().",".
            $c->getAddress();
        }

        // convert each user into string
        $user_str = "userID,password,role,firstName,lastName,profilePic,signUpDate,gender,phoneNumber,email";

        foreach(self::$users as $u){
            $user_str .= "\n".
            $u->getUserID().",".
            $u->getPassword().",".
            $u->getRole().",".
            $u->getFirstName().",".
            $u->getLastName().",".
            $u->getProfilePic().",".
            $u->getSignUpDate().",".
            $u->getGender().",".
            $u->getPhoneNumber().",".
            $u->getEmail();
        }

        // paths to the csv files
        $customer_data_path = realpath("../../data/customer.data.csv");
        $user_data_path = realpath("../../data/user.data.csv");
        
        // save them in file
        FileService::writeFile($customer_data_path, $customer_str);
        FileService::writeFile($user_data_path, $user_str);

        return $profile;

    }

    private static function setCustomerProperty(&$customer, $property, $value){
        
        switch ($property){

            case "userID":
                $customer->setUserID($value);
            break;
            case "address":
                $customer->setAddress($value);
            break;

        }

    }

    private static function setUserProperty(&$user, $property, $value){

        switch ($property){

            case "userID":
                $user->setUserID($value);
            break;
            case "password":
                $user->setPassword($value);
            break;
            case "role":
                $user->setRole($value);
            break;
            case "firstName":
                $user->setFirstName($value);
            break;
            case "lastName":
                $user->setLastName($value);
            break;
            case "profilePic":
                $user->setProfilePic($value);
            break;
            case "signUpDate":
                $user->setSignUpDate($value);
            break;
            case "gender":
                $user->setGender($value);
            break;
            case "phoneNumber":
                $user->setPhoneNumber($value);
            break;
            case "email":
                $user->setEmail($value);
            break;
        }

    }

}


?>