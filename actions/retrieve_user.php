<?php
// Create the user from the database data
$User = new User($userDB, $Session->email);

// Assign the user to the current visitor
$Visitor->setuser($User);
