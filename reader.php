<?php
$key = 0xDEADBEEF;
$size = 1024;
$shm = shmop_open($key, 'c', 0666, $size);
$mutex = new SyncMutex('crypto_rate_mutex');

$val = 0;

while (true) {
    $mutex->lock();
    $raw = shmop_read($shm, 0, 16);
    $mutex->unlock();

    ['rate' => $rate, 'timestamp' => $ts] = unpack('drate/dtimestamp', $raw);
    $latency = (microtime(true) - $ts) * 1_000;

    if ($val === $rate ) {
        continue;
    }

    $val = $rate;
    echo sprintf(
        "Read:  rate=%.2f @ %.6f  → latency: %.3f ms\n",
        $rate, $ts, $latency
    );
}
