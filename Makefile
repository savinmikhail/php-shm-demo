.PHONY: run clean

# Запускаем writer и reader параллельно, ждём 6 секунд, показываем логи
run:
	@pkill -f "php writer.php" 2>/dev/null || true
	@pkill -f "php reader.php" 2>/dev/null || true
	@echo "Запуск writer и reader…"
	@php writer.php > writer.log &
	@php reader.php > reader.log &
	@sleep 6
	@echo "\n--- writer.log ---"
	@cat writer.log
	@echo "\n--- reader.log ---"
	@cat reader.log
	@pkill -f "php writer.php" 2>/dev/null || true
	@pkill -f "php reader.php" 2>/dev/null || true
	@make clean

clean:
	-@rm -f writer.log reader.log

up:
	docker build -t php-shm-demo . && docker run --rm --name demo -v "$(CURDIR)":/app php-shm-demo
