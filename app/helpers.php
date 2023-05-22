<?php

function isSuperAdmin(){
    return ( strcmp( Auth::user()->rol, 'super_administrator') == 0 ) ? true : false;
}

function formatRol(){
    return ( strcmp( Auth::user()->rol, 'super_administrator') == 0 ) ? 'Super Administrator' : 'Administrator';
}