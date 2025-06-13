<?php

$key = 0xDEADBEEF;
$size = 1024;
$shm = shmop_open($key, 'c', 0666, $size);
$mutex = new SyncMutex('crypto_rate_mutex');

for ($i = 0; $i < 10; $i++) {
    // — simulate rate fetch —
    $rate = 50000 + random_int(-1000, 1000) / 100.0;
    $timestamp = microtime(true);

    // pack rate+ts as two doubles
    $payload = pack('dd', $rate, $timestamp);

    $mutex->lock();
    shmop_write($shm, $payload, 0);
    $mutex->unlock();

    echo sprintf("Wrote: rate=%.2f @ %.6f\n", $rate, $timestamp);
    usleep(500_000);
}
