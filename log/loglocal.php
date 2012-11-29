<?php

$bufferSize = $_SERVER['argc'] > 1 ? $_SERVER['argv'][1] : 3;
$context = new ZMQContext();
$in = $context->getSocket(ZMQ::SOCKET_PULL);
$out = $context->getSocket(ZMQ::SOCKET_PUSH);
$in->bind("ipc:///tmp/logger");
$out->connect("tcp://localhost:5555");

$messages = array();
while (true) {
   $message = $in->recv();
   echo "Received Log", PHP_EOL;
   $messages[] = $message;
   if (count($messages) == $bufferSize) {
       echo "Forwarding Buffer", PHP_EOL;
       $out->sendMulti($messages);
       $messages = array();
   }
}
