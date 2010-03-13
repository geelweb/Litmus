#
# Litmus Customer API Implementation Makefile
# use make help to get the targets

# Binary
PHP = php
PHPUNIT = phpunit
PHPDOC = phpdoc

# Path
ROOT = .
PROJECT_LIB_PATH = ${ROOT}/library
PROJECT_TEST_PATH = ${ROOT}/tests
PROJECT_DOC_PATH = ${ROOT}/doc

# Display an help message
all: help
help: 
	@echo "Makefile usage:"
	@echo "\nmake doc"
	@echo "\tTo generate the phpDoc documentation using the doc/phpDocumentor.ini config file"
	@echo "\nmake unit-test"
	@echo "\t To execute the phpUnitTest using the tests/phpunit.xml config file"

# Generate the doc
doc:
	@echo "Generate phpDoc documentation :"
	@${PHPDOC} -c ${PROJECT_DOC_PATH}/phpDocumentor.ini
	@echo "done"


# Exec PHP unitTest
unit-test:
	@echo "----------------"
	@echo "Exec PHPUnits test:"
	@cd ${PROJECT_TEST_PATH} && ${PHPUNIT} --configuration phpunit.xml
	@echo "done"

.PHONY: doc 
