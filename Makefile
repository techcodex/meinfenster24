.PHONY: up test build
up:    ; docker compose up --build
test:  ; docker compose --profile test run --rm --build test
build: ; npm run build
