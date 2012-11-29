<?php

$context = new ZMQContext();
$sock = $context->getSocket(ZMQ::SOCKET_SUB);
$sock->setSockOpt(ZMQ::SOCKOPT_SUBSCRIBE, "");
$sock->connect("tcp://localhost:5656");

while (true) {
    var_dump(json_decode($sock->recv()));
}
