<?php
    //returns connection to 
    function getConnection()
    {
        //Local variables to maintain encapsulation
        $servername = "cosc304.ok.ubc.ca";
        $username = "btruong";
        $password = "btruong";
        $dbname = "db_btruong";
		
        //Create connection
        $con = new mysqli($servername, $username, $password, $dbname);

        //Check connection
        if ($con -> connect_error)
        {
            die("Connection failed: ".$con -> connect_error);
        }
		
        return $con;
    }
?>