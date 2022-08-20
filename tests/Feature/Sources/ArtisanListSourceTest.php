<?php

namespace Juampi92\Phecks\Tests\Feature\Sources;

use Illuminate\Contracts\Console\Kernel;
use Juampi92\Phecks\Domain\DTOs\MatchValue;
use Juampi92\Phecks\Domain\Sources\ArtisanListSource;
use Juampi92\Phecks\Domain\Sources\ValueObjects\ArtisanCommandInfo;
use Juampi92\Phecks\Tests\Feature\TestCase;

class ArtisanListSourceTest extends TestCase
{
    public function test_should_not_show_hidden(): void
    {
        // Arrange
        /** @var Kernel $kernel */
        $kernel = $this->app->make(Kernel::class);
        $kernel->registerCommand(new stubs\TestCommand());
        $kernel->registerCommand(new stubs\TestHiddenCommand());

        /** @var ArtisanListSource $source */
        $source = resolve(ArtisanListSource::class);

        // Act
        $result = $source->run();

        // Assert
        $this->assertTrue(
            $result->getItems()->contains(
                /** @param MatchValue<ArtisanCommandInfo> $match */
                fn (MatchValue $match): bool => $match->value->name === 'my-command:foo:bar'
            ),
        );
        $this->assertFalse(
            $result->getItems()->contains(
                /** @param MatchValue<ArtisanCommandInfo> $match */
                fn (MatchValue $match): bool => $match->value->name === 'my-command:foo:hidden'
            ),
            'It should not show the hidden commands'
        );
    }

    public function test_should_show_hidden(): void
    {
        // Arrange
        /** @var Kernel $kernel */
        $kernel = $this->app->make(Kernel::class);
        $kernel->registerCommand(new stubs\TestCommand());
        $kernel->registerCommand(new stubs\TestHiddenCommand());

        /** @var ArtisanListSource $source */
        $source = resolve(ArtisanListSource::class);

        // Act
        $result = $source->showHidden()->run();

        // Assert
        $this->assertTrue(
            $result->getItems()->contains(
                /** @param MatchValue<ArtisanCommandInfo> $match */
                fn (MatchValue $match): bool => $match->value->name === 'my-command:foo:bar'
            ),
        );
        $this->assertTrue(
            $result->getItems()->contains(
                /** @param MatchValue<ArtisanCommandInfo> $match */
                fn (MatchValue $match): bool => $match->value->name === 'my-command:foo:hidden'
            ),
            'It should show the hidden commands'
        );
    }

    public function test_should_format_the_output_correctly(): void
    {
        // Arrange
        /** @var Kernel $kernel */
        $kernel = $this->app->make(Kernel::class);
        $kernel->registerCommand(new stubs\TestCommand());

        /** @var ArtisanListSource $source */
        $source = resolve(ArtisanListSource::class);

        // Act
        $result = $source->showHidden()->run();

        // Assert
        /** @var ArtisanCommandInfo $fooBarCommand */
        $fooBarCommand = $result->getItems()->first(
            /** @param MatchValue<ArtisanCommandInfo> $match */
            fn (MatchValue $match): bool => $match->value->name === 'my-command:foo:bar'
        )->value;

        $this->assertEquals('FooBar', $fooBarCommand->description);
        $this->assertEquals('This is an argument', $fooBarCommand->arguments['argument']->description);
        $this->assertEquals('This is an option', $fooBarCommand->options['option']->description);
    }
}
