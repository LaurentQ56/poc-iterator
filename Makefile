
# —— Setup ————————————————————————————————————————————————————————————————————————
DC             = docker-compose
PROJECT_DIR    = /iterator
RUN            = $(DC) run --rm
RUN_SERVER     = $(RUN) -w $(PROJECT_DIR)/src
EXEC           = $(DC) exec
GIT_AUTHOR     = Dev-Int

.DEFAULT_GOAL :=help
.PHONY: help start down build up install

check_defined = \
    $(strip $(foreach 1,$1, \
        $(call __check_defined,$1,$(strip $(value 2)))))
__check_defined = \
    $(if $(value $1),, \
        $(error Undefined $1$(if $2, ($2))$(if $(value @), \
                required by target `$@')))


## —— The Fifo Makefile ———————————————————————————————————————————————————————————

help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'


## —— Project —————————————————————————————————————————————————————————————————————

start: up #db-test ## start the project

down: ## Remove docker containers
	@echo "Stopping containers"
	@$(DC) down
	@echo "Container stopped"

reset: down start ## Reset the whole project

install: dist-files build start ## Install the whole project

php_sh: ## Open bash in php container
	@$(EXEC) php bash


## —— Test ————————————————————————————————————————————————————————————————————————
test: phpunit.xml ## Test application
	@$(EXEC) php vendor/bin/phpunit


## —— Dependencies ————————————————————————————————————————————————————————————————

# Internal rules
dist-files: #phpunit.xml.dist
#	@echo "Copy .dist files"
#	@cp phpunit.xml.dist phpunit.xml

build:
	@echo "Building images"
	@$(DC) build > /dev/null

up:
	@echo "Starting containers"
	@$(DC) up -d --remove-orphans
