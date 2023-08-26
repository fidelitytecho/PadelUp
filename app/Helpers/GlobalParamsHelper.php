<?php
namespace App\Helpers;


    /**
     * Retrieve Customer Data Related to Auth User
     *
     */
    if ( !function_exists('customerData') )
    {
        function customerData(){
            return auth('api')->user()->load('Customer')->Customer;
        }
    }
