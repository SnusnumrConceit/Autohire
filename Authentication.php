<?php
    function Authenticate($user)
    {
        setcookie("Account[{$user[0]->id}]", "{$user[0]->Password}", time() + 3600, '/');
    }

?>