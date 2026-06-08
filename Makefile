PHP ?= php
COMPOSER ?= composer
HOST ?= localhost
PORT ?= 8000
EMAIL ?= admin@example.com
NAME ?= Admin User
PASSWORD ?= change-this-password

.PHONY: help install setup migrate seed-menu-items seed-admin backup serve lint

help:
	@printf "Available targets:\n"
	@printf "  make install                         Install Composer dependencies\n"
	@printf "  make setup                           Install dependencies, migrate, and seed demo menu items\n"
	@printf "  make migrate                         Run SQLite migrations\n"
	@printf "  make seed-menu-items                 Seed reusable Peruvian and Mexican menu items\n"
	@printf "  make seed-admin EMAIL=... NAME=... PASSWORD=...\n"
	@printf "                                      Create or update an admin user\n"
	@printf "  make backup                          Create a timestamped SQLite backup\n"
	@printf "  make serve                           Run local server on HOST:PORT\n"
	@printf "  make lint                            Lint app PHP files\n"

install:
	$(COMPOSER) install

setup: install migrate seed-menu-items

migrate:
	$(PHP) scripts/migrate.php

seed-menu-items:
	$(PHP) scripts/seed_menu_items.php

seed-admin:
	$(PHP) scripts/seed_admin.php "$(EMAIL)" "$(NAME)" "$(PASSWORD)"

backup:
	$(PHP) scripts/backup_sqlite.php

serve:
	$(PHP) -S $(HOST):$(PORT) -t public

lint:
	@set -e; \
	for file in $$(git ls-files '*.php' && git ls-files --others --exclude-standard '*.php'); do \
		$(PHP) -l "$$file"; \
	done
