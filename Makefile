default: cs-fix

cs-fix:
	vendor/bin/php-cs-fixer fix --config-file=.php_cs
