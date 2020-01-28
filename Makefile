test: code_coverage

code_metric:
	@vendor/bin/phpmd app text phpmd_rulesets.xml
	@vendor/bin/phpmd tests text phpmd_rulesets.xml

code_standard: code_metric
	@vendor/bin/phpcs app --standard=PSR2 -n
	@vendor/bin/phpcs tests --standard=PSR2 -n

unit_test: code_standard
	@vendor/bin/phpunit --debug --stop-on-failure --stop-on-error

code_coverage: unit_test
	@php artisan news:coverage-check
