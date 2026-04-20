<?php
require_once 'supabase.php';

$data = supabaseRequest("songs?select=*");

echo json_encode($data);
