<?php

namespace spec\Artisans\Guardian;

use Illuminate\Contracts\Foundation\Application;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GuardianSpec extends ObjectBehavior {

	function let(Application $app)
	{
		$this->beConstructedWith($app);
	}

	function it_is_initializable(Application $app)
	{
		$this->shouldHaveType('Artisans\Guardian\Guardian');
	}

	function it_should_return_true_on_can_method(Application $app)
	{
		$this->can()->shouldReturn(true);
	}

}
