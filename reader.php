<?php
// Настройка shared memory и мьютекса
$key    = 0xDEADBEEF;
$size   = 1024;
$shm    = shmop_open($key, 'a', 0, 0);
$mutex  = new SyncMutex('crypto_rate_mutex');

for ($i = 0; $i < 10; $i++) {
    // Критическая секция
    $mutex->lock();
    $data = shmop_read($shm, 0, 8);
    $mutex->unlock();

    // Распаковываем и вычисляем задержку в мс
    $written = unpack('d', $data)[1];
    $now     = microtime(true);
    $latency = ($now - $written) * 1000;

    echo sprintf("Latency: %.3f ms\n", $latency);
    usleep(500000); // 0.5 секунды
}

