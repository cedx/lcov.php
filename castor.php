<?php declare(strict_types=1);

use Castor\Attribute\{AsContext, AsTask};
use Castor\Context;
use function Castor\{exit_code, finder, fs, run, variable};

#[AsContext(default: true)]
function context(): Context {
	return new Context(["package" => json_decode(file_get_contents("composer.json"))]);
}

#[AsTask(description: "Deletes all generated files")]
function clean(): void {
	fs()->remove(finder()->in("var"));
}

#[AsTask(description: "Performs the static analysis of source code")]
function lint(): int {
	return _exec("phpstan", "analyse", "--configuration=etc/phpstan.php", "--memory-limit=256M", "--verbose");
}

#[AsTask(description: "Publishes the package")]
function publish(): void {
	$pkg = variable("package");
	foreach (["tag", "push origin"] as $action) run("git $action v$pkg->version");
}

#[AsTask(description: "Runs the test suite")]
function test(): int {
	return _exec("phpunit", "--configuration=etc/phpunit.xml");
}

/**
 * Executes a command from a local package.
 * @param string $command The command to run.
 * @param string ...$args The command arguments.
 * @return int The exit code.
 */
function _exec(string $command, string ...$args): int {
	return exit_code(["composer", "exec", "--", $command, ...$args]);
}
