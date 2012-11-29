<?php

$context = new ZMQContext();
$req =
  new ZMQSocket($context, ZMQ::SOCKET_REQ);
$req->connect("tcp://localhost:5454");

$req->send("Hello");
echo $req->recv();
echo PHP_EOL;
