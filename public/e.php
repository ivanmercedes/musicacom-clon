<?php

echo var_dump(unserialize('a:2:{i:0;s:1:"3";i:1;s:1:"2";}'));

// $text = 'Tengo tantas ganas
// Ay, de besarte en las mañanas, justo cuando te levantas (Te amo)
// Pero tengo miedo (Tengo miedo)
// Que busques a alguien perfecto y yo tan de carne y hueso (Te amo)

// Si pudiera controlar el tiempo
// Yo volvería a esperar de nuevo
// Una y mil veces, pa\' ver cómo amaneces';



// $post = nl2br($text, false);
// $post = '<p>' . preg_replace('#(<br>[\r\n]+){2}#', "</p>\n\n<p>", $post) . '</p>';

// // $post = nl2br($text);
// // $post = '<p>' . preg_replace('#(<br>[\r\n]+){2}#', '</p><p>', $post) . '</p>';

// echo substr(uniqid(rand()),0,10);