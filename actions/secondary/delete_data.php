<?php
// Unset the session
$Session->remove();

// The whole thing (sent when headers are sent)
$Cookie->remove();
