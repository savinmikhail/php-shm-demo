<?php
// Настройка shared memory и мьютекса
$key    = 0xDEADBEEF;
$size   = 1024;
$shm    = shmop_open($key, 'c', 0666, $size);
$mutex  = new SyncMutex('crypto_rate_mutex');

for ($i = 0; $i < 10; $i++) {
    // Текущий timestamp в качестве double
    $timestamp = microtime(true);
    // Упаковываем в бинарный double (8 байт)
    $payload = pack('d', $timestamp);

    // Критическая секция
    $mutex->lock();
    shmop_write($shm, $payload, 0);
    $mutex->unlock();

    echo sprintf("Wrote: %.6f\n", $timestamp);
    usleep(500000); // 0.5 секунды
}

