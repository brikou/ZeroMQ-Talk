<?php

$context = new ZMQContext();

// socket clients
$frontend = new ZMQSocket($context, ZMQ::SOCKET_XREP);
$frontend->bind("tcp://*:5454");

// socket workers
$backend = new ZMQSocket($context, ZMQ::SOCKET_XREQ);
$backend->bind("tcp://*:5455");

$poll = new ZMQPoll();
$poll->add($frontend, ZMQ::POLL_IN);
$poll->add($backend, ZMQ::POLL_IN);

$readable = $writeable = array();

while (true) {
    $events = $poll->poll($readable, $writeable);
    foreach ($readable as $socket) {
        if ($socket === $frontend) {
            $messages = $frontend->recvMulti();
            $backend->sendMulti($messages);
        } elseif ($socket === $backend) {
            $messages = $backend->recvMulti();
            $frontend->sendMulti($messages);
        }
    }
}
